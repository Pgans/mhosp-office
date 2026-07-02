<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "usefor".
 *
 * @property int $usefor_id
 * @property string $usefor_name ลักษณะการใช้งาน
 */
class Usefor extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'usefor';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['usefor_name'], 'required'],
            [['usefor_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'usefor_id' => Yii::t('app', 'Usefor ID'),
            'usefor_name' => Yii::t('app', 'ลักษณะการใช้งาน'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return UseforQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UseforQuery(get_called_class());
    }
}
