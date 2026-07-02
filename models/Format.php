<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "format".
 *
 * @property int $format_id
 * @property string $format_name รูปแบบการจัด
 */
class Format extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'format';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['format_name'], 'required'],
            [['format_name'], 'string', 'max' => 45],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'format_id' => Yii::t('app', 'Format ID'),
            'format_name' => Yii::t('app', 'รูปแบบการจัด'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return FormatQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new FormatQuery(get_called_class());
    }
}
