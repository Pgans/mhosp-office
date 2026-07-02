<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "belows".
 *
 * @property string $below_id รหัสล่าง
 * @property string $below_price ราคาล่าง
 *
 * @property LotteryBills[] $lotteryBills
 */
class Belows extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'belows';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['below_price'], 'string', 'max' => 2],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'below_id' => 'Below ID',
            'below_price' => 'Below Price',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLotteryBills()
    {
        return $this->hasMany(LotteryBills::className(), ['below_id' => 'below_id']);
    }
}
