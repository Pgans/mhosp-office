<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "amphur".
 *
 * @property string $AMPHUR_ID
 * @property string $AMPHUR_CODE
 * @property string $AMPHUR_NAME
 * @property int $GEO_ID
 * @property string $PROVINCE_ID
 *
 * @property OrderOils[] $orderOils
 */
class Amphur extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'amphur';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['GEO_ID', 'PROVINCE_ID'], 'integer'],
            [['AMPHUR_CODE'], 'string', 'max' => 4],
            [['AMPHUR_NAME'], 'string', 'max' => 150],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'AMPHUR_ID' => 'Amphur ID',
            'AMPHUR_CODE' => 'Amphur Code',
            'AMPHUR_NAME' => 'Amphur Name',
            'GEO_ID' => 'Geo ID',
            'PROVINCE_ID' => 'Province ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderOils()
    {
        return $this->hasMany(OrderOils::className(), ['amphur_id' => 'AMPHUR_ID']);
    }
}
