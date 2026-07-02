<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Agendaitem;

/**
 * AgendaitemSearch represents the model behind the search form of `app\models\Agendaitem`.
 */
class AgendaitemSearch extends Agendaitem
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['agenda_id', 'meeting_agenda_id', 'view'], 'integer'],
            [['ref', 'topic', 'discription', 'covenant', 'docs', 'create_date'], 'safe'],
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
        $query = Agendaitem::find();

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
            'agenda_id' => $this->agenda_id,
            'meeting_agenda_id' => $this->meeting_agenda_id,
            'create_date' => $this->create_date,
            'view' => $this->view,
        ]);

        $query->andFilterWhere(['like', 'ref', $this->ref])
            ->andFilterWhere(['like', 'topic', $this->topic])
            ->andFilterWhere(['like', 'discription', $this->discription])
            ->andFilterWhere(['like', 'covenant', $this->covenant])
            ->andFilterWhere(['like', 'docs', $this->docs]);

        return $dataProvider;
    }
}
