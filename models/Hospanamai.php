<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "hosp_anamai".
 *
 * @property string $anamai_id รหัสรพ.สต
 * @property string $hospcode รหัสสถานพบาบาล
 * @property string $hospname ชื่อสถานพยาบาล
 *
 * @property OrderOils[] $orderOils
 * @property OrderOils2[] $orderOils2s
 */
class Hospanamai extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'hosp_anamai';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['hospcode'], 'string', 'max' => 20],
            [['hospname'], 'string', 'max' => 150],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'anamai_id' => 'Anamai ID',
            'hospcode' => 'Hospcode',
            'hospname' => 'Hospname',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderOils()
    {
        return $this->hasMany(OrderOils::className(), ['anamai_id' => 'anamai_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderOils2s()
    {
        return $this->hasMany(OrderOils2::className(), ['anamai_id' => 'anamai_id']);
    }
}
