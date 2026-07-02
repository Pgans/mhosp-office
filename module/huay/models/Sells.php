<?php

namespace app\module\huay\models;

use Yii;

/**
 * This is the model class for table "sells".
 *
 * @property string $sell_id รหัสผู้ขาย
 * @property string $sell_name ชื่อเล่น
 *
 * @property LotteryAll[] $lotteryAlls
 */
class Sells extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sells';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sell_name'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'sell_id' => 'Sell ID',
            'sell_name' => 'Sell Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLotteryAlls()
    {
        return $this->hasMany(LotteryAll::className(), ['sell_id' => 'sell_id']);
    }
}
