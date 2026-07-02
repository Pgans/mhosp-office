<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "treatments".
 *
 * @property int $id
 * @property string $treatment_name เพื่อ
 *
 * @property Permitsori[] $permitsoris
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
            'id' => 'ID',
            'treatment_name' => 'Treatment Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPermitsoris()
    {
        return $this->hasMany(Permitsori::className(), ['treatments_id' => 'id']);
    }
}
