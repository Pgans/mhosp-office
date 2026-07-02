<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%service_units}}".
 *
 * @property string $UNIT_ID
 * @property string $UNIT_NAME
 * @property string $UNIT_DESCRIPTION
 * @property string $DPT_ID
 * @property int $auto_id
 * @property string $clinic_code รหัสมาตรฐานแผนก เพื่อส่งออก 43 แฟ้ม
 * @property string $prefix_q ัตัวอักษรนำหน้าในเบอร์คิว
 * @property string $line_token
 * @property string $service
 * @property int $mipd แสดงใน iPad round
 * @property string $f16 รหัส 16แฟ้ม
 * @property string $chatID
 */
class ServiceUnits extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%service_units}}';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db14');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['UNIT_ID', 'UNIT_NAME', 'UNIT_DESCRIPTION', 'DPT_ID', 'clinic_code', 'prefix_q', 'service', 'mipd', 'chatID'], 'required'],
            [['mipd'], 'integer'],
            [['UNIT_ID', 'DPT_ID', 'clinic_code', 'f16'], 'string', 'max' => 2],
            [['UNIT_NAME'], 'string', 'max' => 40],
            [['UNIT_DESCRIPTION'], 'string', 'max' => 120],
            [['prefix_q', 'service'], 'string', 'max' => 3],
            [['line_token', 'chatID'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'UNIT_ID' => Yii::t('app', 'Unit ID'),
            'UNIT_NAME' => Yii::t('app', 'Unit Name'),
            'UNIT_DESCRIPTION' => Yii::t('app', 'Unit Description'),
            'DPT_ID' => Yii::t('app', 'Dpt ID'),
            'auto_id' => Yii::t('app', 'Auto ID'),
            'clinic_code' => Yii::t('app', 'Clinic Code'),
            'prefix_q' => Yii::t('app', 'Prefix Q'),
            'line_token' => Yii::t('app', 'Line Token'),
            'service' => Yii::t('app', 'Service'),
            'mipd' => Yii::t('app', 'Mipd'),
            'f16' => Yii::t('app', 'F16'),
            'chatID' => Yii::t('app', 'Chat ID'),
        ];
    }
}
