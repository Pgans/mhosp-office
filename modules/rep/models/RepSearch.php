<?php

namespace app\modules\rep\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\rep\models\Rep;

/**
 * RepSearch represents the model behind the search form of `app\modules\rep\models\Rep`.
 */
class RepSearch extends Rep
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ID'], 'integer'],
            [['REP', 'SEQ', 'TRAN_ID', 'HN', 'AN', 'PID', 'FULLNAME', 'INSCL', 'REGDATE', 'COMPEN', 'INST'], 'safe'],
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
        $query->andFilterWhere([
            'ID' => $this->ID,
            'REGDATE' => $this->REGDATE,
        ]);

        $query->andFilterWhere(['like', 'REP', $this->REP])
            ->andFilterWhere(['like', 'SEQ', $this->SEQ])
            ->andFilterWhere(['like', 'TRAN_ID', $this->TRAN_ID])
            ->andFilterWhere(['like', 'HN', $this->HN])
            ->andFilterWhere(['like', 'AN', $this->AN])
            ->andFilterWhere(['like', 'PID', $this->PID])
            ->andFilterWhere(['like', 'FULLNAME', $this->FULLNAME])
            ->andFilterWhere(['like', 'INSCL', $this->INSCL])
            ->andFilterWhere(['like', 'COMPEN', $this->COMPEN])
            ->andFilterWhere(['like', 'INST', $this->INST]);

        return $dataProvider;
    }
}
