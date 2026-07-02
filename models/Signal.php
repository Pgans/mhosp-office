<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "signal".
 *
 * @property int $signal_id รห้สสัญญาณ
 * @property string $name ชื่อความเร่ง
 *
 * @property Jobservice[] $jobservices
 */
class Signal extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'signal';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 150],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'signal_id' => 'Signal ID',
            'name' => 'Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJobservices()
    {
        return $this->hasMany(Jobservice::className(), ['signal_id' => 'signal_id']);
    }
}
