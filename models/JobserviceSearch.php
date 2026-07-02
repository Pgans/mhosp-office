<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Jobservice;

/**
 * JobserviceSearch represents the model behind the search form of `app\models\Jobservice`.
 */
class JobserviceSearch extends Jobservice
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'repair_by', 'device_id', 'jstatus_id', 'type_id', 'dep_id', 'signal_id'], 'integer'],
            [['detail', 'dateline', 'send_by', 'send_at', 'updated_at', 'repair_service', 'service_by'], 'safe'],
            [['repair_cost'], 'number'],
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
        $query = Jobservice::find();

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
            'dateline' => $this->dateline,
            'send_at' => $this->send_at,
            'repair_by' => $this->repair_by,
            'updated_at' => $this->updated_at,
            'repair_cost' => $this->repair_cost,
            'device_id' => $this->device_id,
            'jstatus_id' => $this->jstatus_id,
            'type_id' => $this->type_id,
            'dep_id' => $this->dep_id,
            'signal_id' => $this->signal_id,
        ]);

        $query->andFilterWhere(['like', 'detail', $this->detail])
            ->andFilterWhere(['like', 'send_by', $this->send_by])
            ->andFilterWhere(['like', 'repair_service', $this->repair_service])
            ->andFilterWhere(['like', 'service_by', $this->service_by]);

        return $dataProvider;
    }
}
