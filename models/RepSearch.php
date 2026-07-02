<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Rep;

/**
 * RegisterdataSearch represents the model behind the search form of `app\models\Registerdata`.
 */
class RepSearch extends Rep
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ECLAIM_NO', 'HCODE', 'HN', 'AN', 'DATEADM', 'TIMEADM', 'DATEDSC', 'TIMEDSC', 'PERSON_TYPE', 'PID', 'IDTYPE', 'TITLES', 'FNAME', 'LNAME', 'DOB', 'SEX', 'WEIGHT', 'AGE', 'AGE_MM', 'MARRY_STATUS', 'NATIONS', 'OCCUPA', 'HMAIN', 'INSCL', 'MAININSCL', 'PROHMAIN', 'PROZONE', 'DEMAND_CLAIM_OPD', 'DEMAND_CLAIM_IPD', 'CLAIMCODE', 'PROJCODE', 'REFER_STATUS', 'HMAIN_REFER_IN', 'REFER_IN_CAUSE', 'HMAIN_REFER_OUT', 'REFER_OUT_CAUSE', 'REFERCODE', 'DISCHS', 'DISCHT', 'LOS', 'SVCTYPE', 'UUC', 'AETYPE', 'AEDATE', 'AETIME', 'STATUS', 'PARENT_ECLAIM_NO', 'DATETIMEADJ', 'HMAIN2', 'PROV1', 'PROV2', 'CLAIM_C', 'CLAIM_T', 'HC', 'PDX', 'PDX_NAME', 'PDX_DRDX', 'SUMS_HC', 'SUMS_PPCOM', 'SUMS_SERVICEITEM', 'TOTALPAID', 'TOTALEXPPAID', 'TOTALEMERPAID', 'DRG', 'RW', 'ADJRW', 'DRG_NHSO', 'RW_NHSO', 'ADJRW_NHSO', 'SOURCE', 'CHKFLAG', 'FILENAME', 'EXPORTDATE', 'CODE_ID', 'HCODE_SEND', 'DATE_SEND', 'FILENAME_SEND', 'DATA_TYPE', 'TYPE_RETURN', 'FILENAME_IN', 'TIME_STAMP', 'NOTEDATE', 'RECNO', 'RESNO', 'EPAC_ID', 'REP', 'ATTACHFILES', 'TRAN_ID', 'appeal_type', 'DEPARTMENT_CODE', 'DEPARTMENT_NAME', 'DOCNO', 'OWNRIGHTPID', 'OWNNAME', 'HASH', 'RELATE_INSCL', 'OPDSEQ', 'CONTRACT_TYPE', 'AESTATUS', 'ALERT_DATE', 'ALERT_TIME'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Rep::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere(['like', 'ECLAIM_NO', $this->ECLAIM_NO])
            ->andFilterWhere(['like', 'HCODE', $this->HCODE])
            ->andFilterWhere(['like', 'HN', $this->HN])
            ->andFilterWhere(['like', 'AN', $this->AN])
            ->andFilterWhere(['like', 'DATEADM', $this->DATEADM])
            ->andFilterWhere(['like', 'TIMEADM', $this->TIMEADM])
            ->andFilterWhere(['like', 'DATEDSC', $this->DATEDSC])
            ->andFilterWhere(['like', 'TIMEDSC', $this->TIMEDSC])
            ->andFilterWhere(['like', 'PERSON_TYPE', $this->PERSON_TYPE])
            ->andFilterWhere(['like', 'PID', $this->PID])
            ->andFilterWhere(['like', 'IDTYPE', $this->IDTYPE])
            ->andFilterWhere(['like', 'TITLES', $this->TITLES])
            ->andFilterWhere(['like', 'FNAME', $this->FNAME])
            ->andFilterWhere(['like', 'LNAME', $this->LNAME])
            ->andFilterWhere(['like', 'DOB', $this->DOB])
            ->andFilterWhere(['like', 'SEX', $this->SEX])
            ->andFilterWhere(['like', 'WEIGHT', $this->WEIGHT])
            ->andFilterWhere(['like', 'AGE', $this->AGE])
            ->andFilterWhere(['like', 'AGE_MM', $this->AGE_MM])
            ->andFilterWhere(['like', 'MARRY_STATUS', $this->MARRY_STATUS])
            ->andFilterWhere(['like', 'NATIONS', $this->NATIONS])
            ->andFilterWhere(['like', 'OCCUPA', $this->OCCUPA])
            ->andFilterWhere(['like', 'HMAIN', $this->HMAIN])
            ->andFilterWhere(['like', 'INSCL', $this->INSCL])
            ->andFilterWhere(['like', 'MAININSCL', $this->MAININSCL])
            ->andFilterWhere(['like', 'PROHMAIN', $this->PROHMAIN])
            ->andFilterWhere(['like', 'PROZONE', $this->PROZONE])
            ->andFilterWhere(['like', 'DEMAND_CLAIM_OPD', $this->DEMAND_CLAIM_OPD])
            ->andFilterWhere(['like', 'DEMAND_CLAIM_IPD', $this->DEMAND_CLAIM_IPD])
            ->andFilterWhere(['like', 'CLAIMCODE', $this->CLAIMCODE])
            ->andFilterWhere(['like', 'PROJCODE', $this->PROJCODE])
            ->andFilterWhere(['like', 'REFER_STATUS', $this->REFER_STATUS])
            ->andFilterWhere(['like', 'HMAIN_REFER_IN', $this->HMAIN_REFER_IN])
            ->andFilterWhere(['like', 'REFER_IN_CAUSE', $this->REFER_IN_CAUSE])
            ->andFilterWhere(['like', 'HMAIN_REFER_OUT', $this->HMAIN_REFER_OUT])
            ->andFilterWhere(['like', 'REFER_OUT_CAUSE', $this->REFER_OUT_CAUSE])
            ->andFilterWhere(['like', 'REFERCODE', $this->REFERCODE])
            ->andFilterWhere(['like', 'DISCHS', $this->DISCHS])
            ->andFilterWhere(['like', 'DISCHT', $this->DISCHT])
            ->andFilterWhere(['like', 'LOS', $this->LOS])
            ->andFilterWhere(['like', 'SVCTYPE', $this->SVCTYPE])
            ->andFilterWhere(['like', 'UUC', $this->UUC])
            ->andFilterWhere(['like', 'AETYPE', $this->AETYPE])
            ->andFilterWhere(['like', 'AEDATE', $this->AEDATE])
            ->andFilterWhere(['like', 'AETIME', $this->AETIME])
            ->andFilterWhere(['like', 'STATUS', $this->STATUS])
            ->andFilterWhere(['like', 'PARENT_ECLAIM_NO', $this->PARENT_ECLAIM_NO])
            ->andFilterWhere(['like', 'DATETIMEADJ', $this->DATETIMEADJ])
            ->andFilterWhere(['like', 'HMAIN2', $this->HMAIN2])
            ->andFilterWhere(['like', 'PROV1', $this->PROV1])
            ->andFilterWhere(['like', 'PROV2', $this->PROV2])
            ->andFilterWhere(['like', 'CLAIM_C', $this->CLAIM_C])
            ->andFilterWhere(['like', 'CLAIM_T', $this->CLAIM_T])
            ->andFilterWhere(['like', 'HC', $this->HC])
            ->andFilterWhere(['like', 'PDX', $this->PDX])
            ->andFilterWhere(['like', 'PDX_NAME', $this->PDX_NAME])
            ->andFilterWhere(['like', 'PDX_DRDX', $this->PDX_DRDX])
            ->andFilterWhere(['like', 'SUMS_HC', $this->SUMS_HC])
            ->andFilterWhere(['like', 'SUMS_PPCOM', $this->SUMS_PPCOM])
            ->andFilterWhere(['like', 'SUMS_SERVICEITEM', $this->SUMS_SERVICEITEM])
            ->andFilterWhere(['like', 'TOTALPAID', $this->TOTALPAID])
            ->andFilterWhere(['like', 'TOTALEXPPAID', $this->TOTALEXPPAID])
            ->andFilterWhere(['like', 'TOTALEMERPAID', $this->TOTALEMERPAID])
            ->andFilterWhere(['like', 'DRG', $this->DRG])
            ->andFilterWhere(['like', 'RW', $this->RW])
            ->andFilterWhere(['like', 'ADJRW', $this->ADJRW])
            ->andFilterWhere(['like', 'DRG_NHSO', $this->DRG_NHSO])
            ->andFilterWhere(['like', 'RW_NHSO', $this->RW_NHSO])
            ->andFilterWhere(['like', 'ADJRW_NHSO', $this->ADJRW_NHSO])
            ->andFilterWhere(['like', 'SOURCE', $this->SOURCE])
            ->andFilterWhere(['like', 'CHKFLAG', $this->CHKFLAG])
            ->andFilterWhere(['like', 'FILENAME', $this->FILENAME])
            ->andFilterWhere(['like', 'EXPORTDATE', $this->EXPORTDATE])
            ->andFilterWhere(['like', 'CODE_ID', $this->CODE_ID])
            ->andFilterWhere(['like', 'HCODE_SEND', $this->HCODE_SEND])
            ->andFilterWhere(['like', 'DATE_SEND', $this->DATE_SEND])
            ->andFilterWhere(['like', 'FILENAME_SEND', $this->FILENAME_SEND])
            ->andFilterWhere(['like', 'DATA_TYPE', $this->DATA_TYPE])
            ->andFilterWhere(['like', 'TYPE_RETURN', $this->TYPE_RETURN])
            ->andFilterWhere(['like', 'FILENAME_IN', $this->FILENAME_IN])
            ->andFilterWhere(['like', 'TIME_STAMP', $this->TIME_STAMP])
            ->andFilterWhere(['like', 'NOTEDATE', $this->NOTEDATE])
            ->andFilterWhere(['like', 'RECNO', $this->RECNO])
            ->andFilterWhere(['like', 'RESNO', $this->RESNO])
            ->andFilterWhere(['like', 'EPAC_ID', $this->EPAC_ID])
            ->andFilterWhere(['like', 'REP', $this->REP])
            ->andFilterWhere(['like', 'ATTACHFILES', $this->ATTACHFILES])
            ->andFilterWhere(['like', 'TRAN_ID', $this->TRAN_ID])
            ->andFilterWhere(['like', 'appeal_type', $this->appeal_type])
            ->andFilterWhere(['like', 'DEPARTMENT_CODE', $this->DEPARTMENT_CODE])
            ->andFilterWhere(['like', 'DEPARTMENT_NAME', $this->DEPARTMENT_NAME])
            ->andFilterWhere(['like', 'DOCNO', $this->DOCNO])
            ->andFilterWhere(['like', 'OWNRIGHTPID', $this->OWNRIGHTPID])
            ->andFilterWhere(['like', 'OWNNAME', $this->OWNNAME])
            ->andFilterWhere(['like', 'HASH', $this->HASH])
            ->andFilterWhere(['like', 'RELATE_INSCL', $this->RELATE_INSCL])
            ->andFilterWhere(['like', 'OPDSEQ', $this->OPDSEQ])
            ->andFilterWhere(['like', 'CONTRACT_TYPE', $this->CONTRACT_TYPE])
            ->andFilterWhere(['like', 'AESTATUS', $this->AESTATUS])
            ->andFilterWhere(['like', 'ALERT_DATE', $this->ALERT_DATE])
            ->andFilterWhere(['like', 'ALERT_TIME', $this->ALERT_TIME]);

        return $dataProvider;
    }
}
