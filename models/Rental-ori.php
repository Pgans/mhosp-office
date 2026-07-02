<?php

namespace app\models;

use Yii;
use app\models\User;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "rental".
 *
 * @property int $id
 * @property string $destination จุดหมาย
 * @property int $passenger จำนวนผู้โดยสาร
 * @property string $description รายละเอียด
 * @property string $date_start เวลาเริ่ม
 * @property string $date_end เวลาสิ้นสุด
 * @property string $creat_at
 * @property string $update_at
 * @property string $status
 * @property int $user_id
 * @property int $vehicle_id
 * @property int $driver_id พนักงานขับรถ
 *
 * @property Person $user
 * @property Vehicle $vehicle
 * @property Drivers $driver
 */
class Rental extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rental';
    }
    public function behaviors()
    {
        return [
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'user_id',
               // 'updatedByAttribute' => 'updater_id',
            ],
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['passenger', 'user_id', 'vehicle_id', 'driver_id'], 'integer'],
            [['description', 'status'], 'string'],
            [['date_start', 'date_end', 'creat_at', 'update_at'], 'safe'],
            [['destination'], 'string', 'max' => 45],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Person::className(), 'targetAttribute' => ['user_id' => 'user_id']],
            [['vehicle_id'], 'exist', 'skipOnError' => true, 'targetClass' => Vehicle::className(), 'targetAttribute' => ['vehicle_id' => 'vehicle_id']],
            [['driver_id'], 'exist', 'skipOnError' => true, 'targetClass' => Drivers::className(), 'targetAttribute' => ['driver_id' => 'driver_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'destination' => Yii::t('app', 'จุดหมาย'),
            'passenger' => Yii::t('app', 'จำนวนผู้โดยสาร'),
            'description' => Yii::t('app', 'รายละเอียด'),
            'date_start' => Yii::t('app', 'เวลาเริ่ม'),
            'date_end' => Yii::t('app', 'เวลาสิ้นสุด'),
            'creat_at' => Yii::t('app', 'เพิ่มข้อมูลเมื่อ'),
            'update_at' => Yii::t('app', 'แก้ไขเมื่อ'),
            'status' => Yii::t('app', 'สถานะ'),
            'user_id' => Yii::t('app', 'ผู้จอง'),
            'vehicle_id' => Yii::t('app', 'ทะเบียนรถ'),
            'driver_id' => Yii::t('app', 'พนักงานขับรถ'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Person::className(), ['user_id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVehicle()
    {
        return $this->hasOne(Vehicle::className(), ['vehicle_id' => 'vehicle_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDriver()
    {
        return $this->hasOne(Drivers::className(), ['driver_id' => 'driver_id']);
    }
    public function getUpdater(){
        return $this->hasOne(User::className(), ['user_id' => 'updater_id']);
    }

    /**
     * {@inheritdoc}
     * @return RentalQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new RentalQuery(get_called_class());
    }
}
