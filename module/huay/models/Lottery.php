<?php

namespace app\module\huay\models;

use Yii;

/**
 * This is the model class for table "lottery_bills".
 *
 * @property string $lottery_id รหัส
 * @property string $numbers เลข
 * @property string $bon_id รหัสบน
 * @property string $below_id รหัสล่าง
 * @property string $d_update วันบันทึก
 *
 * @property Belows $below
 * @property Bon $bon
 */
class Lottery extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'lottery_bills';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            //[['numbers', 'bon_id', 'below_id','sell_id'], 'required'],
            [['bon_id', 'below_id','sell_id'], 'integer'],
            [['d_update'], 'safe'],
            [['numbers'], 'string', 'max' => 2],
            [['below_id'], 'exist', 'skipOnError' => true, 'targetClass' => Belows::className(), 'targetAttribute' => ['below_id' => 'below_id']],
            [['bon_id'], 'exist', 'skipOnError' => true, 'targetClass' => Bon::className(), 'targetAttribute' => ['bon_id' => 'bon_id']],
            [['sell_id'], 'exist', 'skipOnError' => true, 'targetClass' => Sells::className(), 'targetAttribute' => ['sell_id' => 'sell_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'lottery_id' => 'Lottery ID',
            'numbers' => 'Numbers',
            'bon_id' => 'Bon ID',
            'below_id' => 'Below ID',
            'sell_id' => 'Sell ID',
            'd_update' => 'D Update',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBelow()
    {
        return $this->hasOne(Belows::className(), ['below_id' => 'below_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBon()
    {
        return $this->hasOne(Bon::className(), ['bon_id' => 'bon_id']);
    }
    public function getSells()
    {
        return $this->hasOne(Sells::className(), ['sell_id' => 'sell_name']);
    }
}
