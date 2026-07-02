<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "department_job".
 *
 * @property int $dep_id รหัสแผนก
 * @property string $dep_name ชื่อแผนก
 *
 * @property Jobservice[] $jobservices
 */
class DepartmentJob extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'department_job';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['dep_name'], 'required'],
            [['dep_name'], 'string', 'max' => 250],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'dep_id' => 'Dep ID',
            'dep_name' => 'Dep Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJobservices()
    {
        return $this->hasMany(Jobservice::className(), ['dep_id' => 'dep_id']);
    }
}
