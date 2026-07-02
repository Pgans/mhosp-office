<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "l_subfund".
 *
 * @property int $ID
 * @property string $SUB_FUND
 */
class Lsubfund extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'l_subfund';
    }
    public static function getDb()
    {
        return Yii::$app->get('db1');
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['SUB_FUND'], 'string', 'max' => 25],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'SUB_FUND' => 'Sub  Fund',
        ];
    }
}
