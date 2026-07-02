<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;
use yii\db\Expression;


/**
 * This is the model class for table "jobservice".
 *
 * @property int $id เลขที่ส่งซ่อม
 * @property string $detail รายละเอียดแจ้งซ่อม
 * @property string $dateline วันทีต้องการ
 * @property string $send_by ผู้แจ้ง
 * @property string $send_at วันที่่แจ้งซ่อม
 * @property int $repair_by ผู้รับซ่อม
 * @property string $updated_at วันทีซ่อม
 * @property string $repair_service
 * @property int $repair_cost ราคาซ่อม
 * @property int $device_id
 * @property int $jstatus_id
 * @property int $type_id
 *
 * @property Devices $device
 * @property Jobtype $type
 * @property Jobstatus $jstatus
 */
class Jobservice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'jobservice';
    }
    public function behaviors()
  {
      return [
          [
                    'class' => TimestampBehavior::className(),
                    'createdAtAttribute' => 'send_at',
                    'updatedAtAttribute' => 'updated_at',
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
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['detail', 'dateline', 'send_by','service_by'], 'required'],
            [['dateline', 'send_at', 'updated_at'], 'safe'],
            [['repair_by', 'device_id', 'jstatus_id', 'type_id'], 'integer'],
            [['repair_cost'], 'number'],
            [['detail', 'repair_service'], 'string', 'max' => 250],
            [['send_by'], 'string', 'max' => 100],
            [['device_id'], 'exist', 'skipOnError' => true, 'targetClass' => Devices::className(), 'targetAttribute' => ['device_id' => 'device_id']],
            [['type_id'], 'exist', 'skipOnError' => true, 'targetClass' => Jobtype::className(), 'targetAttribute' => ['type_id' => 'id']],
            [['jstatus_id'], 'exist', 'skipOnError' => true, 'targetClass' => Jobstatus::className(), 'targetAttribute' => ['jstatus_id' => 'id']],
            [['dep_id'], 'exist', 'skipOnError' => true, 'targetClass' => Departmentjob::className(), 'targetAttribute' => ['dep_id' => 'dep_id']],
            [['signal_id'], 'exist', 'skipOnError' => true, 'targetClass' => Signal::className(), 'targetAttribute' => ['signal_id' => 'signal_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'detail' => 'กิจกรรมปัญหา',
            'dateline' => 'วันที่ต้องการ',
            'send_by' => 'ผู้แจ้งซ่อม ',
            'send_at' => 'วันที่แจ้ง',
            'repair_by' => 'ผู้รับทราบ',
            'updated_at' => 'วันที่ซ่อม',
            'repair_service' => 'การแก้ไข',
            'repair_cost' => 'ราคาซ่อม',
            'device_id' => 'เลขครุภัณฑ์',
            'jstatus_id' => 'สถานะซ่อม',
            'type_id' => 'ประเภทงาน',
            'signal_id'=>'ความเร่ง',
            'dep_id'=>'แผนก',
			'service_by'=>'ผู้ซ่อม',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getService()
    {
        return $this->hasOne(Services::className(), ['device_id' => 'device_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(Jobtypeservice::className(), ['id' => 'type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJstatus()
    {
        return $this->hasOne(Jobstatus::className(), ['id' => 'jstatus_id']);
    }
    public function getUpdater()
    {
        return $this->hasOne(Person::className(), ['user_id' => 'repair_by']);
    }
    public function getDepartment()
    {
        return $this->hasOne(Departmentjob::className(), ['dep_id' => 'dep_id']);
    }
    public function getSignal()
    {
        return $this->hasOne(Signal::className(), ['signal_id' => 'signal_id']);
    }
}
