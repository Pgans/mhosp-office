<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Logclosevisitsj;

/**
 * LogclosevisitsjSearch represents the model behind the search form of `app\models\Logclosevisitsj`.
 */
class LogclosevisitsjSearch extends Logclosevisitsj
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['visit_id', 'pid', 'status', 'messagecode', 'response', 'transaction_uid', 'users', 'send_date', 'regdate'], 'safe'],
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
        $query = Logclosevisitsj::find();

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
]);

// ใช้สำหรับการค้นหาแบบระบุช่วงวันที่
$query->andFilterWhere(['>=', 'send_date', $this->send_date ? date('Y-m-d 00:00:00', strtotime($this->send_date)) : null])
      ->andFilterWhere(['<=', 'send_date', $this->send_date ? date('Y-m-d 23:59:59', strtotime($this->send_date)) : null]);

$query->andFilterWhere(['>=', 'regdate', $this->regdate ? date('Y-m-d 00:00:00', strtotime($this->regdate)) : null])
      ->andFilterWhere(['<=', 'regdate', $this->regdate ? date('Y-m-d 23:59:59', strtotime($this->regdate)) : null]);

$query->andFilterWhere(['like', 'visit_id', $this->visit_id])
      ->andFilterWhere(['like', 'pid', $this->pid])
      ->andFilterWhere(['like', 'status', $this->status])
      ->andFilterWhere(['like', 'messagecode', $this->messagecode])
      ->andFilterWhere(['like', 'response', $this->response])
      ->andFilterWhere(['like', 'transaction_uid', $this->transaction_uid])
      ->andFilterWhere(['like', 'users', $this->users]);

return $dataProvider;

    }
}
