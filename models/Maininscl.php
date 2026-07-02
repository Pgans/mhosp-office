<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%main_inscls}}".
 *
 * @property string $INSCL
 * @property string $INSCL_NAME
 * @property string $NHSO_CODE
 * @property string $STD_CODE
 * @property string $inscl_group
 * @property int $auto_id
 * @property int $is_cancel
 */
class Maininscl extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%main_inscls}}';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db14');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['INSCL', 'INSCL_NAME', 'NHSO_CODE', 'STD_CODE', 'inscl_group', 'is_cancel'], 'required'],
            [['is_cancel'], 'integer'],
            [['INSCL'], 'string', 'max' => 2],
            [['INSCL_NAME'], 'string', 'max' => 80],
            [['NHSO_CODE'], 'string', 'max' => 3],
            [['STD_CODE'], 'string', 'max' => 4],
            [['inscl_group'], 'string', 'max' => 30],
            [['INSCL'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'INSCL' => Yii::t('app', 'Inscl'),
            'INSCL_NAME' => Yii::t('app', 'Inscl Name'),
            'NHSO_CODE' => Yii::t('app', 'Nhso Code'),
            'STD_CODE' => Yii::t('app', 'Std Code'),
            'inscl_group' => Yii::t('app', 'Inscl Group'),
            'auto_id' => Yii::t('app', 'Auto ID'),
            'is_cancel' => Yii::t('app', 'Is Cancel'),
        ];
    }
}
