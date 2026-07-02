<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "departments".
 *
 * @property int $dep_id รหัสหน่วยงาน
 * @property string $dep_name ชื่อหน่วยงาน
 *
 * @property History[] $histories
 * @property Person[] $people
 * @property Personsss[] $personssses
 */
class Departments extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'departments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['dep_name'], 'required'],
            [['dep_name'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'dep_id' => 'Dep ID',
            'dep_name' => 'Dep Name',
        ];
    }
	

    /**
     * Gets query for [[Histories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getHistories()
    {
        return $this->hasMany(History::className(), ['departments_dep_id' => 'dep_id']);
    }

    /**
     * Gets query for [[People]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPeople()
    {
        return $this->hasMany(Person::className(), ['dep_id' => 'dep_id']);
    }

    /**
     * Gets query for [[Personssses]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPersonssses()
    {
        return $this->hasMany(Personsss::className(), ['dep_id' => 'dep_id']);
    }
}
