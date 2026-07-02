<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Agendasubx;

/**
 * AgendasubxSearch represents the model behind the search form of `app\models\Agendasubx`.
 */
class AgendasubxSearch extends Agendasubx
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sub_id', 'meeting_id', 'agenda_id'], 'integer'],
            [['sub_topic', 'sub_description', 'department', 'filename', 'path', 'create_date'], 'safe'],
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
        $query = Agendasubx::find();

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
            'sub_id' => $this->sub_id,
            'meeting_id' => $this->meeting_id,
            'agenda_id' => $this->agenda_id,
            'create_date' => $this->create_date,
        ]);

        $query->andFilterWhere(['like', 'sub_topic', $this->sub_topic])
            ->andFilterWhere(['like', 'sub_description', $this->sub_description])
            ->andFilterWhere(['like', 'department', $this->department])
            ->andFilterWhere(['like', 'filename', $this->filename])
            ->andFilterWhere(['like', 'path', $this->path]);

        return $dataProvider;
    }
}
