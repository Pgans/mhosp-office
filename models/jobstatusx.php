<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "jobstatus".
 *
 * @property int $id เลขที่ประเภทงาน
 * @property string $status ประเภทงาน
 *
 * @property Jobcom[] $jobcoms
 */
class Jobstatus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'jobstatus';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status'], 'required'],
            [['status'], 'string', 'max' => 250],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'สถานะการซ่อม',
            'status' => 'สถานะ',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJobcoms()
    {
        return $this->hasMany(Jobcom::className(), ['jstatus_id' => 'id']);
    }
}
