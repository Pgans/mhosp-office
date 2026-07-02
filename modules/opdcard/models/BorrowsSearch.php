<?php

namespace app\modules\opdcard\Model;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\opdcard\model\Borrows;

/**
 * BorrowsSearch represents the model behind the search form of `app\modules\opdcard\model\Borrows`.
 */
class BorrowsSearch extends Borrows
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'treatments_id', 'updated_by', 'status_id'], 'integer'],
            [['an', 'created_by', 'created_at', 'updated_at', 'day_want'], 'safe'],
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
        $query = Borrows::find();

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
            'treatments_id' => $this->treatments_id,
            'created_at' => $this->created_at,
            'updated_by' => $this->updated_by,
            'updated_at' => $this->updated_at,
            'status_id' => $this->status_id,
            'day_want' => $this->day_want,
        ]);

        $query->andFilterWhere(['like', 'an', $this->an])
            ->andFilterWhere(['like', 'created_by', $this->created_by]);

        return $dataProvider;
    }
}
