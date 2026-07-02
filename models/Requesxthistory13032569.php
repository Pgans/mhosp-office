<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;
use yii\db\Expression;
/**
 * This is the model class for table "request_history".
 *
 * @property int $id รหัสยยืม
 * @property string $no เลขที่หนังสือ
 * @property string $cid เลขประชาชน
 * @property string $hn เลขโรงพยาบาล
 * @property string $fullname ชื่อผู้ป่วย
 * @property int $assemble_id เพื่อ
 * @property int $created_by ผู้ยืม
 * @property string $created_at วันที่ยืม
 * @property int $updated_by ผู้รับคืน
 * @property string $updated_at วันที่คิน
 * @property int $status_id สถานะ
 * @property string $day_want วันที่ต้องการ
 */
class Requesxthistory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'request_history';
    }
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
            [['assemble_id', 'created_by', 'updated_by', 'status_id'], 'integer'],
            [['created_at', 'updated_at', 'day_want','start_date','end_date'], 'safe'],
            [['no'], 'string', 'max' => 20],
            [['cid'], 'string', 'max' => 13],
            [['hn'], 'string', 'max' => 7],
            [['fullname', 'orther'], 'string', 'max' => 150],
        ];
    }

    /**
     * {@inheritdoc}
     */

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'รหัส'),
            'no' => Yii::t('app', 'เลขที่หนังสือ'),
            'cid' => Yii::t('app', 'เลขประชาชน'),
            'hn' => Yii::t('app', 'HN'),
            'fullname' => Yii::t('app', 'ชื่อผู้ป่วย'),
            'assemble' => Yii::t('app', 'เพื่อประกอบ'),
            'created_by' => Yii::t('app', 'ผู้บันทึก'),
            'created_at' => Yii::t('app', 'วันที่ขอ'),
            'updated_by' => Yii::t('app', 'ผู้พิมพ์ประวัติ'),
            'updated_at' => Yii::t('app', 'วันที่พิมพ์'),
            'status_id' => Yii::t('app', 'สถานะ'),
            'day_want' => Yii::t('app', 'วันที่ส่งมอบ'),
			'start_date' => Yii::t('app', 'วันเข้ารักษา'),
			'end_date' => Yii::t('app', 'วันจำหน่าย'),
			'orther' => Yii::t('app', 'พิมพ์ย้อนหลัง'),
        ];
    }
    public function getCreatedBy()
    {
        return $this->hasOne(Person::className(), ['user_id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(StatusHistory::className(), ['id' => 'status_id']);
    }
    public function getPerson()
    {
        return $this->hasOne(Person::class, ['user_id' => 'user_id']);
    }
    public function getAssemble()
    {
        return $this->hasOne(Assemble::className(), ['id' => 'assemble_id']);
    }
    public function getUpdater()
    {
        return $this->hasOne(Person::className(), ['user_id' => 'updated_by']);
    }
}


