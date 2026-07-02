<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\helpers\ArrayHelper;
use yii\db\Expression;


/**
 * This is the model class for table "jobcom".
 *
 * @property int $id เลขที่ส่งซ่อม
 * @property string $detail รายละเอียดแจ้งซ่อม
 * @property string $dateline วันทีต้องการ
 * @property string $send_by ผู้แจ้ง
 * @property string $send_at วันที่่แจ้งซ่อม
 * @property int $repair_by ผู้รับซ่อม
 * @property string $repair_at วันทีซ่อม
 * @property string $repair_service
 * @property int $repair_cost ราคาซ่อม
 * @property int $device_id รหัสอุปกรณ์fk
 * @property int $jstatus_id รหัสสถานะfk
 * @property int $type_id รหัสชนิดfk
 * @property int $dep_id รหัสแผนกfk
 *
 * @property Devices $device
 * @property Jobtype $type
 * @property Jobstatus $jstatus
 */
class Jobcom extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'jobcom';
    }
	   public function behaviors()
  {
      return [
          [
                    'class' => TimestampBehavior::className(),
                    'createdAtAttribute' => 'send_at',
                    'updatedAtAttribute' => 'repair_at',
                    'value' => new Expression( 'NOW()' ),
                ],
                [
                    'class' => BlameableBehavior::className(),
                    //'createdByAttribute' => 'created_by',
                    'updatedByAttribute' => 'repair_by',
                ],

      ];
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['detail', 'dateline', 'send_by', 'dep_id'], 'required'],
            [['dateline', 'send_at', 'repair_at'], 'safe'],
            [['repair_by', 'repair_cost', 'device_id', 'jstatus_id', 'type_id', 'dep_id'], 'integer'],
            [['detail', 'repair_service'], 'string', 'max' => 250],
            [['send_by'], 'string', 'max' => 100],
            [['device_id'], 'exist', 'skipOnError' => true, 'targetClass' => Devices::className(), 'targetAttribute' => ['device_id' => 'device_id']],
            [['type_id'], 'exist', 'skipOnError' => true, 'targetClass' => Jobtype::className(), 'targetAttribute' => ['type_id' => 'id']],
            [['jstatus_id'], 'exist', 'skipOnError' => true, 'targetClass' => Jobstatus::className(), 'targetAttribute' => ['jstatus_id' => 'id']],
			[['dep_id'], 'exist', 'skipOnError' => true, 'targetClass' => departmentjob::className(), 'targetAttribute' => ['dep_id' => 'dep_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'detail' => 'รายละเอียดการแจ้งซ่อม',
            'dateline' => 'วันทีต้องการ',
            'send_by' => 'ผู้แจ้ง',
            'send_at' => 'Send At',
            'repair_by' => 'Repair By',
            'repair_at' => 'Repair At',
            'repair_service' => 'การแก้ไข',
            'repair_cost' => 'ค่าซ่อม',
            'device_id' => 'Device ID',
            'jstatus_id' => 'สถานะ',
            'type_id' => 'ประเภท',
            'dep_id' => 'แผนก',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDevice()
    {
        return $this->hasOne(Devices::className(), ['device_id' => 'device_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(Jobtype::className(), ['id' => 'type_id']);
    }
    public function getUpdater()
    {
        return $this->hasOne(Person::className(), ['user_id' => 'repair_by']);
    }
   public function getDepartment(){
        return $this->hasOne(departmentjob::className(),['dep_id'=>'dep_id']);
    }
	 public function getJstatus()
    {
        return $this->hasOne(Jobstatus::className(), ['id' => 'jstatus_id']);
    }
    public function getColor()
    {
        return $this->hasOne(Jobstatus::className(), ['id' => 'color']);
    }


    
}