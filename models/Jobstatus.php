<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "jobstatus".
 *
 * @property int $id เลขที่ประเภทงาน
 * @property string $status ประเภทงาน
 * @property string $color สี
 * @property string $code
 *
 * @property Jobcom[] $jobcoms
 * @property Jobcomx[] $jobcomxes
 * @property Jobservice[] $jobservices
 */
class Jobstatus extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'jobstatus';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status'], 'required'],
            [['status'], 'string', 'max' => 250],
            [['color'], 'string', 'max' => 100],
            [['code'], 'string', 'max' => 5],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status' => 'Status',
            'color' => 'Color',
            'code' => 'Code',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJobcoms()
    {
        return $this->hasMany(Jobcom::className(), ['jstatus_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJobcomxes()
    {
        return $this->hasMany(Jobcomx::className(), ['jstatus_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJobservices()
    {
        return $this->hasMany(Jobservice::className(), ['jstatus_id' => 'id']);
    }
}
