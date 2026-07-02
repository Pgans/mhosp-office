<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "assemble".
 *
 * @property int $id
 * @property string $assemble_name เพื่อ
 */
class Assemble extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'assemble';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['assemble_name'], 'required'],
            [['assemble_name'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'assemble_name' => Yii::t('app', 'เพื่อ'),
        ];
    }
}
