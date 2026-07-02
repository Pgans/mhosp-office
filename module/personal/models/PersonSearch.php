<?php

namespace app\module\personal\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Person;

/**
 * PersonSearch represents the model behind the search form about `common\models\Person`.
 */
class PersonSearch extends Person
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'dep_id', 'positions_id'], 'integer'],
            [['firstname', 'lastname', 'photo', 'birthdate', 'start_date', 'stop_date'], 'safe'],
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
    $query = Person::find();

    $dataProvider = new ActiveDataProvider([
        'query' => $query,
        'pagination'=> [
            'pagesize'=> 8
        ]
    ]);

    $this->load($params);

    if (!$this->validate()) {
        // uncomment the following line if you do not want to return any records when validation fails
        // $query->where('0=1');
        return $dataProvider;
    }

    // เพิ่มเงื่อนไขเพื่อกรอง stop_date ที่ไม่เท่ากับ '0000-00-00'
    $query->andWhere(['stop_date' => '0000-00-00']);

    $query->andFilterWhere([
        'user_id' => $this->user_id,
        'birthdate' => $this->birthdate,
        'start_date' => $this->start_date,
        'dep_id' => $this->dep_id,
        'positions_id' => $this->positions_id,
    ]);

    $query->andFilterWhere(['like', 'firstname', $this->firstname])
        ->andFilterWhere(['like', 'lastname', $this->lastname])
        ->andFilterWhere(['like', 'photo', $this->photo]);

    return $dataProvider;
}

}
