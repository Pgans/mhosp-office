<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "oils".
 *
 * @property string $oil_id รหัสนำมัน
 * @property string $oil_name ชื่อนำมันเชื้อเพลิง
 *
 * @property OrderOils2[] $orderOils2s
 * @property OrderoilTest[] $orderoilTests
 */
class Oils extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'oils';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['oil_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'oil_id' => 'Oil ID',
            'oil_name' => 'Oil Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderOils2s()
    {
        return $this->hasMany(OrderOils2::className(), ['oil_id' => 'oil_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderoilTests()
    {
        return $this->hasMany(OrderoilTest::className(), ['oil_id' => 'oil_id']);
    }
}
