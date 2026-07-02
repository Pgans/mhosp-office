<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "devices".
 *
 * @property int $device_id รหัสอุปกรณ์
 * @property string $device_serial หมายเลขครุภัณฑ์
 * @property string $device_name ชื่ออุุปกรณ์
 * @property int $category_id รหัสประเภทอุปกรณ์
 * @property int $dep_id รหัสแผนกFK
 * @property string $spec รุ่น ยี่ห้อ
 * @property string $purchase_date วันที่ซื้อ
 * @property string $due_date วันครบกำหนด
 * @property int $price ราคา
 * @property string $sale_date วันจำหน่าย
 * @property string $orther หมายเหตุ
 *
 * @property Jobcom[] $jobcoms
 * @property Jobcomx[] $jobcomxes
 * @property Jobservice[] $jobservices
 */
class Devices extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'devices';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['device_serial', 'device_name', 'category_id', 'dep_id', 'spec', 'purchase_date', 'due_date', 'price'], 'required'],
            [['category_id', 'dep_id', 'price'], 'integer'],
            [['purchase_date', 'due_date', 'sale_date'], 'safe'],
            [['device_serial'], 'string', 'max' => 20],
            [['device_name'], 'string', 'max' => 50],
            [['spec', 'orther'], 'string', 'max' => 254],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'device_id' => 'Device ID',
            'device_serial' => 'Device Serial',
            'device_name' => 'Device Name',
            'category_id' => 'Category ID',
            'dep_id' => 'Dep ID',
            'spec' => 'Spec',
            'purchase_date' => 'Purchase Date',
            'due_date' => 'Due Date',
            'price' => 'Price',
            'sale_date' => 'Sale Date',
            'orther' => 'Orther',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJobcoms()
    {
        return $this->hasMany(Jobcom::className(), ['device_id' => 'device_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJobcomxes()
    {
        return $this->hasMany(Jobcomx::className(), ['device_id' => 'device_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJobservices()
    {
        return $this->hasMany(Jobservice::className(), ['device_id' => 'device_id']);
    }
}
