<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "vehicle".
 *
 * @property int $vehicle_id
 * @property string $license เลขทะเบียน
 * @property string $description รายละเอียด
 * @property string $driver พนักงานขับรถ
 * @property string $photo รูปภาพ
 *
 * @property Rental[] $rentals
 */
class Vehicle extends \yii\db\ActiveRecord
{
    public $vehicle_img;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vehicle';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description'], 'string'],
            [['license', 'photo'], 'string', 'max' => 100],
            [['driver'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'vehicle_id' => Yii::t('app', 'เลขทะเบียนรถ'),
            'license' => Yii::t('app', 'เลขทะเบียน'),
            'description' => Yii::t('app', 'รายละเอียด'),
            'driver' => Yii::t('app', 'พนักงานขับรถ'),
            'photo' => Yii::t('app', 'รูปภาพ'),
            'vehicle_img' => 'รูปภาพ',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRentals()
    {
        return $this->hasMany(Rental::className(), ['vehicle_id' => 'vehicle_id']);
    }

    /**
     * {@inheritdoc}
     * @return VehicleQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new VehicleQuery(get_called_class());
    }
}
