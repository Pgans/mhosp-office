<?php

namespace app\models;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;
use yii\db\Expression;

use Yii;

/**
 * This is the model class for table "permits".
 *
 ** @property int $id
 * @property string $AN AN
 * @property string $HN HN
 * @property string $fullname ชื่อผู้ป่วย
 * @property int $treatments_id เพื่อ
 * @property int $created_by ผู้ยืม
 * @property string $created_at วันที่ยืม
 * @property int $updated_by ผู้รับคืน
 * @property string $updated_at วันที่คิน
 * @property int $status_id สถานะ
 * @property string $day_want
 */
class Permits extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'permits';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
     {
      return [
        //    'timestamp' => [
        //         'class' => TimestampBehavior::className(),
        //         'createdAtAttribute' => 'created_time',
        //         'updatedAtAttribute' => 'updated_time',
        //         'value' => new Expression('NOW()'),//กำหนดค่า หรืออาจใช้ค่าอย่างอื่นที่ return เป็น timestamp ก็ได้
        //     ],
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'value' => new Expression('NOW()'),
            ],
          BlameableBehavior::className(),
      ];
     }
    public function rules()
    {
        return [
            [['HN', 'fullname', 'treatments_id'], 'required'],
            [['treatments_id', 'created_by', 'updated_by', 'status_id'], 'integer'],
            [['created_at','updated_at','day_want'], 'safe'],
            [['AN', 'HN'], 'string', 'max' => 7],
            [['fullname'], 'string', 'max' => 150],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => Person::className(), 'targetAttribute' => ['created_by' => 'user_id']],
            [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => Status::className(), 'targetAttribute' => ['status_id' => 'id']],
            [['treatments_id'], 'exist', 'skipOnError' => true, 'targetClass' => Treatments::className(), 'targetAttribute' => ['treatments_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'AN' => 'AN',
            'HN' => 'HN',
            'fullname' => 'ชื่อผู้ป่วย',
            'treatments_id' => 'เพื่อ',
            'day_want' => 'วันที่ต้องการ',
            'created_by' => 'ผู้ยืม',
            'created_at' => 'วันที่ยืม',
            'updated_by' => 'ผู้รับคืน',
            'updated_at' => 'วันที่คืน',
            'status_id' => 'สถานะ',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(Person::className(), ['user_id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(Status::className(), ['id' => 'status_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTreatments()
    {
        return $this->hasOne(Treatments::className(), ['id' => 'treatments_id']);
    }
    public function getUpdater()
    {
        return $this->hasOne(Person::className(), ['user_id' => 'updated_by']);
    }

}
