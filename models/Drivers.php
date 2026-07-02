<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "drivers".
 *
 * @property int $driver_id รหัสพนักงานขับรถ
 * @property string $driver_name ชื่อพนักงานขับรถ
 * @property string $telephone เบอร์โทร
 *
 * @property Rental[] $rentals
 */
class Drivers extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'drivers';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['driver_name'], 'string', 'max' => 150],
            [['telephone'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'driver_id' => Yii::t('app', 'รหัสพนักงานขับรถ'),
            'driver_name' => Yii::t('app', 'ชื่อพนักงานขับรถ'),
            'telephone' => Yii::t('app', 'เบอร์โทร'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRentals()
    {
        return $this->hasMany(Rental::className(), ['driver_id' => 'driver_id']);
    }

    /**
     * {@inheritdoc}
     * @return DriversQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DriversQuery(get_called_class());
    }
}
