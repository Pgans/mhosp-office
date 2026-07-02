<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "jobtype_service".
 *
 * @property int $id เลขที่ประเภทงาน
 * @property string $type ประเภทงาน
 */
class Jobtypeservice extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'jobtype_service';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type'], 'required'],
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
        ];
    }
}
