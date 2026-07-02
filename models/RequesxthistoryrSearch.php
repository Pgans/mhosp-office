<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Requesxthistory;

/**
 * RequesxthistoryrSearch represents the model behind the search form of `app\models\Requesxthistory`.
 */
class RequesxthistoryrSearch extends Requesxthistory
{
  
    public $cid;
    public $start_date;
    public $end_date;
	
    public function rules()
    {
        return [
            [['id', 'created_by', 'updated_by', 'status_id'], 'integer'],
            [['no', 'cid', 'hn', 'fullname', 'assemble', 'created_at', 'updated_at', 'day_want','start_date','end_date','orther'], 'safe'],
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
        $query = Requesxthistory::find();

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
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_by' => $this->updated_by,
            'updated_at' => $this->updated_at,
            'status_id' => $this->status_id,
            'day_want' => $this->day_want,
			'start_date' => $this->start_date,
			'end_date' => $this->end_date,
        ]);

        $query->andFilterWhere(['like', 'no', $this->no])
            ->andFilterWhere(['like', 'cid', $this->cid])
            ->andFilterWhere(['like', 'hn', $this->hn])
            ->andFilterWhere(['like', 'fullname', $this->fullname])
            ->andFilterWhere(['like', 'assemble', $this->assemble]);

        return $dataProvider;
    }
}
