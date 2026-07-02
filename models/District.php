<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "district".
 *
 * @property string $DISTRICT_ID รหัสตำบล
 * @property string $DISTRICT_CODE เลขตำบล
 * @property string $DISTRICT_NAME ชื่อตำบล
 * @property string $AMPHUR_ID
 * @property string $PROVINCE_ID
 * @property int $GEO_ID
 *
 * @property OrderOils[] $orderOils
 */
class District extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'district';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['DISTRICT_CODE', 'DISTRICT_NAME'], 'required'],
            [['AMPHUR_ID', 'PROVINCE_ID', 'GEO_ID'], 'integer'],
            [['DISTRICT_CODE'], 'string', 'max' => 6],
            [['DISTRICT_NAME'], 'string', 'max' => 150],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'DISTRICT_ID' => 'District ID',
            'DISTRICT_CODE' => 'District Code',
            'DISTRICT_NAME' => 'District Name',
            'AMPHUR_ID' => 'Amphur ID',
            'PROVINCE_ID' => 'Province ID',
            'GEO_ID' => 'Geo ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderOils()
    {
        return $this->hasMany(OrderOils::className(), ['district_id' => 'DISTRICT_ID']);
    }
}
