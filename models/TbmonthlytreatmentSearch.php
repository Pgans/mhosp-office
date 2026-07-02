<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Tbmonthlytreatment;

/**
 * TbmonthlytreatmentSearch represents the model behind the search form of `app\models\Tbmonthlytreatment`.
 */
class TbmonthlytreatmentSearch extends Tbmonthlytreatment
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['hn', 'start_month', 'month2', 'month3', 'month4', 'month5', 'month6', 'month7', 'treatment_detail', 'created_at'], 'safe'],
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
        $query = Tbmonthlytreatment::find();

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
            'id' => $this->id,
            'start_month' => $this->start_month,
            'month2' => $this->month2,
            'month3' => $this->month3,
            'month4' => $this->month4,
            'month5' => $this->month5,
            'month6' => $this->month6,
            'month7' => $this->month7,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'hn', $this->hn])
            ->andFilterWhere(['like', 'treatment_detail', $this->treatment_detail]);

        return $dataProvider;
    }
}
