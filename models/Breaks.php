<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "breaks".
 *
 * @property int $breaks_id
 * @property string $breaks_name รูปแบบการจัดเบรค
 * @property int $breaks_budget ค่าใช้จ่าย
 */
class Breaks extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'breaks';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['breaks_name'], 'required'],
            [['breaks_budget'], 'integer'],
            [['breaks_name'], 'string', 'max' => 150],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'breaks_id' => Yii::t('app', 'Breaks ID'),
            'breaks_name' => Yii::t('app', 'รูปแบบการจัดเบรค'),
            'breaks_budget' => Yii::t('app', 'ค่าใช้จ่าย'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return BreaksQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new BreaksQuery(get_called_class());
    }
}
