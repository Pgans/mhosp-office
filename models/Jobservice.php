<?php

namespace app\models;

use Yii;

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
 * @property string $repair_service ผู้ซ่อม
 * @property string $repair_cost ราคาซ่อม
 * @property int $device_id รหัสอุปกรณ์fk
 * @property int $jstatus_id รหัสสถานะfk
 * @property int $type_id รหัสชนิดfk
 * @property int $dep_id รหัสแผนกfk
 * @property int $signal_id รหัสความเร่ง
 * @property string $service_by ผู้ซ่อม
 *
 * @property Devices $device
 * @property Jobtype $type
 * @property Jobstatus $jstatus
 * @property DepartmentJob $dep
 * @property Signal $signal
 */
class Jobservice extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'jobservice';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['detail', 'dateline', 'send_by', 'dep_id'], 'required'],
            [['dateline', 'send_at', 'updated_at'], 'safe'],
            [['repair_by', 'device_id', 'jstatus_id', 'type_id', 'dep_id', 'signal_id'], 'integer'],
            [['repair_cost'], 'number'],
            [['detail', 'repair_service'], 'string', 'max' => 250],
            [['send_by', 'service_by'], 'string', 'max' => 100],
            [['device_id'], 'exist', 'skipOnError' => true, 'targetClass' => Devices::className(), 'targetAttribute' => ['device_id' => 'device_id']],
            [['type_id'], 'exist', 'skipOnError' => true, 'targetClass' => Jobtype::className(), 'targetAttribute' => ['type_id' => 'id']],
            [['jstatus_id'], 'exist', 'skipOnError' => true, 'targetClass' => Jobstatus::className(), 'targetAttribute' => ['jstatus_id' => 'id']],
            [['dep_id'], 'exist', 'skipOnError' => true, 'targetClass' => DepartmentJob::className(), 'targetAttribute' => ['dep_id' => 'dep_id']],
            [['signal_id'], 'exist', 'skipOnError' => true, 'targetClass' => Signal::className(), 'targetAttribute' => ['signal_id' => 'signal_id']],
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
            'send_at' => Yii::t('app', 'วันที่่แจ้งซ่อม'),
            'repair_by' => Yii::t('app', 'ผู้รับซ่อม'),
            'updated_at' => Yii::t('app', 'วันทีซ่อม'),
            'repair_service' => Yii::t('app', 'ผู้ซ่อม'),
            'repair_cost' => Yii::t('app', 'ราคาซ่อม'),
            'device_id' => Yii::t('app', 'รหัสอุปกรณ์fk'),
            'jstatus_id' => Yii::t('app', 'รหัสสถานะfk'),
            'type_id' => Yii::t('app', 'รหัสชนิดfk'),
            'dep_id' => Yii::t('app', 'รหัสแผนกfk'),
            'signal_id' => Yii::t('app', 'รหัสความเร่ง'),
            'service_by' => Yii::t('app', 'ผู้ซ่อม'),
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJstatus()
    {
        return $this->hasOne(Jobstatus::className(), ['id' => 'jstatus_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDep()
    {
        return $this->hasOne(DepartmentJob::className(), ['dep_id' => 'dep_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSignal()
    {
        return $this->hasOne(Signal::className(), ['signal_id' => 'signal_id']);
    }

    /**
     * {@inheritdoc}
     * @return JobserviceQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new JobserviceQuery(get_called_class());
    }
}
