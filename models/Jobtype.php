<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "jobtype".
 *
 * @property int $id เลขที่ประเภทงาน
 * @property string $type ประเภทงาน
 * @property string $comcategory_id
 *
 * @property Jobcom[] $jobcoms
 * @property Jobcomx[] $jobcomxes
 * @property Jobservice[] $jobservices
 */
class Jobtype extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'jobtype';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type'], 'required'],
            [['comcategory_id'], 'integer'],
            [['type'], 'string', 'max' => 250],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'comcategory_id' => 'Comcategory ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJobcoms()
    {
        return $this->hasMany(Jobcom::className(), ['type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJobcomxes()
    {
        return $this->hasMany(Jobcomx::className(), ['type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJobservices()
    {
        return $this->hasMany(Jobservice::className(), ['type_id' => 'id']);
    }
}
