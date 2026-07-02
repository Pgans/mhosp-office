<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%status}}".
 *
 * @property int $id
 * @property string $status สถานะ
 *
 * @property Permits[] $permitsoris
 */
class Jstatus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%status}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status'], 'required'],
            [['status','color'], 'string', 'max' => 15],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status' => 'Status',
            'color'=>'สี',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPermits()
    {
        return $this->hasMany(Permits::className(), ['status_id' => 'id']);
    }
}
