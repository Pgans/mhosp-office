<?php

namespace app\modules\opdcard\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Permits;

/**
 * PermitsSearch represents the model behind the search form about `frontend\models\Permits`.
 */
class PermitsSearch extends Permits
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','AN', 'HN','treatments_id', 'created_by', 'updated_by', 'status_id'], 'integer'],
            [[ 'fullname', 'day_want','created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
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
        $query = Permits::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pagesize' => 8
            ]
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
            'day_want' => $this->day_want,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_by' => $this->updated_by,
            'updated_at' => $this->updated_at,
            'status_id' => $this->status_id,
        ]);

        $query->andFilterWhere(['like', 'AN', $this->AN])
            ->andFilterWhere(['like', 'HN', $this->HN])
            ->andFilterWhere(['like', 'fullname', $this->fullname]);

        return $dataProvider;
    }
}
