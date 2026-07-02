<?php

namespace app\models;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;
use yii\db\Expression;

use Yii;


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
 * @property int $update_by ผู้แก้ไข
 *
 * @property Person $user
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
          'timestamp' => [
               'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'create_at',
               'updatedAtAttribute' => 'update_at',
                'value' => new Expression('NOW()'),//กำหนดค่า หรืออาจใช้ค่าอย่างอื่นที่ return เป็น timestamp ก็ได้
           ],
           [
            'class' => BlameableBehavior::className(),
            'createdByAttribute' => 'user_id',
            'updatedByAttribute' => 'updated_by',
             ],
        //    'timestamp' => [
        //        'class' => TimestampBehavior::className(),
        //        'value' => new Expression('NOW()'),
        //    ],
         //BlameableBehavior::className(),
     ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['passenger', 'user_id', 'vehicle_id', 'driver_id', 'updated_by'], 'integer'],
            [['description', 'status'], 'string'],
            [['date_start', 'date_end', 'create_at', 'update_at'], 'safe'],
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
            'creat_at' => Yii::t('app', 'Creat At'),
            'update_at' => Yii::t('app', 'Update At'),
            'status' => Yii::t('app', 'Status'),
            'user_id' => Yii::t('app', 'User ID'),
            'vehicle_id' => Yii::t('app', 'Vehicle ID'),
            'driver_id' => Yii::t('app', 'พนักงานขับรถ'),
            'updated_by' => Yii::t('app', 'ผู้อนุมัติ'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    
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
    public function getUpdater()
    {
        return $this->hasOne(Person::className(), ['user_id' => 'updated_by']);
    }
    public function getUser()
    {
        return $this->hasOne(Person::className(), ['user_id' => 'user_id']);
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

