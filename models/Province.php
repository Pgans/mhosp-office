<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "province".
 *
 * @property string $PROVINCE_ID
 * @property string $PROVINCE_CODE
 * @property string $PROVINCE_NAME
 * @property int $GEO_ID
 *
 * @property OrderOils[] $orderOils
 */
class Province extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'province';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['PROVINCE_CODE', 'PROVINCE_NAME'], 'required'],
            [['GEO_ID'], 'integer'],
            [['PROVINCE_CODE'], 'string', 'max' => 2],
            [['PROVINCE_NAME'], 'string', 'max' => 150],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'PROVINCE_ID' => 'Province ID',
            'PROVINCE_CODE' => 'Province Code',
            'PROVINCE_NAME' => 'Province Name',
            'GEO_ID' => 'Geo ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderOils()
    {
        return $this->hasMany(OrderOils::className(), ['province_id' => 'PROVINCE_ID']);
    }
}
