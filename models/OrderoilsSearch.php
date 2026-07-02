<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Orderoils;
/**
 * OrderoilsSearch represents the model behind the search form of `frontend\models\Orderoils`.
 */
class OrderoilsSearch extends Orderoils
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['oilorder_id', 'spray_id', 'province_id', 'amphur_id', 'anamai_id', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'mooban_id','district_id','fullname', 'oils', 'diagnosis', 'd_update'], 'safe'],
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
        $query = Orderoils::find();

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
       // $query->joinWith('mooban');
        
        // grid filtering conditions
        $query->andFilterWhere([
            'oilorder_id' => $this->oilorder_id,
            'created_at' => $this->created_at,
            'spray_id' => $this->spray_id,
            'province_id' => $this->province_id,
            'amphur_id' => $this->amphur_id,
            'district_id' => $this->district_id,
            'mooban_id' => $this->mooban_id,
            'anamai_id' => $this->anamai_id,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'd_update' => $this->d_update,
        ]);

        $query->andFilterWhere(['like', 'fullname', $this->fullname])
            ->andFilterWhere(['like', 'oils', $this->oils])
            ->andFilterWhere(['like', 'diagnosis', $this->diagnosis]);
          //  ->andFilterWhere(['like', 'mooban.mooban_name', $this->mooban_id]);

        return $dataProvider;
    }
}
