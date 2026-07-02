<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Rental;

/**
 * RentalSearch represents the model behind the search form of `app\models\Rental`.
 */
class RentalSearch extends Rental
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'passenger', 'user_id', 'vehicle_id', 'driver_id'], 'integer'],
            [['destination', 'description', 'date_start', 'date_end', 'create_at', 'update_at', 'status'], 'safe'],
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
        $query = Rental::find();

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
            'passenger' => $this->passenger,
            'date_start' => $this->date_start,
            'date_end' => $this->date_end,
            'create_at' => $this->create_at,
            'update_at' => $this->update_at,
            'user_id' => $this->user_id,
            'vehicle_id' => $this->vehicle_id,
            'driver_id' => $this->driver_id,
        ]);

        $query->andFilterWhere(['like', 'destination', $this->destination])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}
