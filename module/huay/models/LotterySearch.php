<?php

namespace app\module\huay\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\module\huay\models\Lottery;

/**
 * LotterySearch represents the model behind the search form of `app\module\huay\models\Lottery`.
 */
class LotterySearch extends Lottery
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['lottery_id', 'bon_id', 'below_id'], 'integer'],
            [['numbers', 'd_update'], 'safe'],
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
        $query = Lottery::find();

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
            'lottery_id' => $this->lottery_id,
            'bon_id' => $this->bon_id,
            'below_id' => $this->below_id,
            'd_update' => $this->d_update,
        ]);

        $query->andFilterWhere(['like', 'numbers', $this->numbers]);

        return $dataProvider;
    }
}
