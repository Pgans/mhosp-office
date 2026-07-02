<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "status_history".
 *
 * @property int $id รหัสสถานะ
 * @property string $status สถานะ
 * @property string $color สี
 */
class StatusHistory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'status_history';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status'], 'required'],
            [['status'], 'string', 'max' => 15],
            [['color'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'รหัสสถานะ'),
            'status' => Yii::t('app', 'สถานะ'),
            'color' => Yii::t('app', 'สี'),
        ];
    }
}
