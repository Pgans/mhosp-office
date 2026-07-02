<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;
use yii\db\Expression;


/**
 * This is the model class for table "rental".
 *
 * @property int $id
 * @property string $destination จุดหมาย
 * @property int $passenger จำนวนผู้โดยสาร
 * @property string $description รายละเอียด
 * @property string $date_start เวลาเริ่ม
 * @property string $date_end เวลาสิ้นสุด
 * @property string $create_at
 * @property string $update_at
 * @property string $status สถานะ
 * @property int $user_id ผู้จอง
 * @property int $vehicle_id
 * @property int $driver_id พนักงานขับรถ
 * @property int $updated_by ผู้แก้ไข
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
            [['date_start', 'date_end'], 'required'],
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
            'passenger' => Yii::t('app', 'จำนวนคน'),
            'description' => Yii::t('app', 'สถานที่ไปราชการ'),
            'date_start' => Yii::t('app', 'เวลาเริ่ม'),
            'date_end' => Yii::t('app', 'เวลาสิ้นสุด'),
            'create_at' => Yii::t('app', 'Create At'),
            'update_at' => Yii::t('app', 'Update At'),
            'status' => Yii::t('app', 'สถานะ'),
            'user_id' => Yii::t('app', 'ผู้จอง'),
            'vehicle_id' => Yii::t('app', 'ทะเบียนรถ'),
            'driver_id' => Yii::t('app', 'พนักงานขับรถ'),
            'updated_by' => Yii::t('app', 'ผู้แก้ไข'),
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
    public function getDepartments()
    {
        return $this->hasOne(Departments::className(), ['dep_id' => 'dep_name']);
    }
    public function getPositions()
    {
        return $this->hasOne(Positions::className(), ['id' => 'positions_id']);
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

