<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "positions".
 *
 * @property int $id รหัสตำแหน่ง
 * @property string $position_name ตำแหน่ง
 *
 * @property Person[] $people
 * @property Personsss[] $personssses
 */
class Positions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'positions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['position_name'], 'required'],
            [['position_name'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'position_name' => 'Position Name',
        ];
    }

    /**
     * Gets query for [[People]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPeople()
    {
        return $this->hasMany(Person::className(), ['positions_id' => 'id']);
    }

    /**
     * Gets query for [[Personssses]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPersonssses()
    {
        return $this->hasMany(Personsss::className(), ['positions_id' => 'id']);
    }
}
