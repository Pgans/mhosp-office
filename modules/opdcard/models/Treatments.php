<?php

namespace app\modules\opdcard\Model;

use Yii;

/**
 * This is the model class for table "treatments".
 *
 * @property int $id
 * @property string $treatment_name เพื่อ
 *
 * @property Borrows[] $borrows
 */
class Treatments extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'treatments';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['treatment_name'], 'required'],
            [['treatment_name'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'treatment_name' => Yii::t('app', 'Treatment Name'),
        ];
    }

    /**
     * Gets query for [[Borrows]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBorrows()
    {
        return $this->hasMany(Borrows::className(), ['treatments_id' => 'id']);
    }
}
