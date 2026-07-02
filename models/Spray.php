<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "spray".
 *
 * @property string $spray_id รหัสพ่นหมอก
 * @property string $spray_name ชื่อกิจกรรมการพ่น
 *
 * @property OrderOils[] $orderOils
 * @property OrderOils2[] $orderOils2s
 */
class Spray extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'spray';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['spray_name'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'spray_id' => 'Spray ID',
            'spray_name' => 'Spray Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderOils()
    {
        return $this->hasMany(OrderOils::className(), ['spray_id' => 'spray_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderOils2s()
    {
        return $this->hasMany(OrderOils2::className(), ['spray_id' => 'spray_id']);
    }
}
