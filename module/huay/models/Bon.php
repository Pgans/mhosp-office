<?php

namespace app\module\huay\models;

use Yii;

/**
 * This is the model class for table "bon".
 *
 * @property string $bon_id รหัสบน
 * @property string $bon_price ราคา
 *
 * @property LotteryBills[] $lotteryBills
 */
class Bon extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bon';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bon_price'], 'string', 'max' => 2],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'bon_id' => 'Bon ID',
            'bon_price' => 'Bon Price',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLotteryBills()
    {
        return $this->hasMany(LotteryBills::className(), ['bon_id' => 'bon_id']);
    }
}
