<?php

namespace app\models;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
//use yii\db\ActiveRecord; // แก้ไขตรงนี้
use yii\db\Expression;
use Yii;

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
class Jobmedical extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'jobmedical';
    }
public function behaviors()
{
    return [
        [
            'class' => TimestampBehavior::className(),
            'createdAtAttribute' => 'send_at',
            'updatedAtAttribute' => 'repair_at',
            'value' => new Expression('NOW()'),
        ],
        [
            'class' => BlameableBehavior::className(),
            'updatedByAttribute' => 'repair_by',
            'createdByAttribute' => null,
        ],
    ];
}

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
			[['dep_id'], 'required', 'message' => 'กรุณาเลือกแผนก'],
            [['jstatus_id'], 'exist', 'skipOnError' => true, 'targetClass' => Jobstatus::className(), 'targetAttribute' => ['jstatus_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'เลขที่ส่งซ่อม'),
            'detail' => Yii::t('app', 'รายละเอียดแจ้งซ่อม'),
            'dateline' => Yii::t('app', 'วันทีต้องการ'),
            'send_by' => Yii::t('app', 'ผู้แจ้ง'),
            'send_at' => Yii::t('app', 'วันที่่แจ้ง'),
            'repair_by' => Yii::t('app', 'ผู้รับซ่อม'),
            'repair_at' => Yii::t('app', 'วันทีซ่อม'),
            'repair_service' => Yii::t('app', 'การแก้ไข'),
            'repair_cost' => Yii::t('app', 'ราคาซ่อม'),
            'device_id' => Yii::t('app', 'รหัสอุปกรณ์'),
            'jstatus_id' => Yii::t('app', 'สถานะ'),
            'type_id' => Yii::t('app', 'ประเภทdการซ่อม'),
            'dep_id' => Yii::t('app', 'แผนก'),
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
	 public function getDepartment()
    {
        return $this->hasOne(Departmentjob::className(), ['dep_id' => 'dep_name']);
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
    /**
     * {@inheritdoc}
     * @return JobcomQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new JobcomQuery(get_called_class());
    }
}
