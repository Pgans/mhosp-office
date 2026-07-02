<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Logclosevisits;

/**
 * LogclosevisitsSearch represents the model behind the search form of `app\models\Logclosevisits`.
 */
class LogclosevisitsSearch extends Logclosevisits
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['visit_id', 'pid', 'status', 'messagecode', 'response', 'transaction_uid', 'users', 'send_date'], 'safe'],
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
        $query = Logclosevisits::find();

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

        $query->andFilterWhere(['id' => $this->id]);

// ค้นหาวันที่ที่เจาะจง
if (!empty($this->send_date)) {
    // ตัวอย่างค้นหาวันที่เดียว
    $query->andFilterWhere(['DATE(send_date)' => $this->send_date]);
    
    // ถ้าคุณต้องการค้นหาช่วงวันที่
    // ตัวอย่างค้นหาจากวันที่เริ่มต้นถึงวันที่สิ้นสุด
    // $query->andFilterWhere(['>=', 'send_date', '2024-09-01'])
    //       ->andFilterWhere(['<=', 'send_date', '2024-09-30']);
}

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
