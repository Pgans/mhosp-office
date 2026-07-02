<?php

namespace app\modules\opdcard\Model;

use Yii;

/**
 * This is the model class for table "borrows".
 *
 * @property int $id รหัสยยืม
 * @property string|null $an AN
 * @property int $treatments_id เพื่อ
 * @property string|null $created_by ผู้ยืม
 * @property string|null $created_at วันที่ยืม
 * @property int|null $updated_by ผู้รับคืน
 * @property string|null $updated_at วันที่คิน
 * @property int|null $status_id สถานะ
 * @property string|null $day_want วันที่ต้องการ
 *
 * @property Status $status
 * @property Treatments $treatments
 */
class Borrows extends \yii\db\ActiveRecord
{
    public $items;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'borrows';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db2');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['treatments_id'], 'required'],
            [['treatments_id', 'updated_by', 'status_id'], 'integer'],
            [['created_at', 'updated_at', 'day_want'], 'safe'],
            [['an'], 'string', 'max' => 6],
            [['created_by'], 'string', 'max' => 13],
            [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => Status::className(), 'targetAttribute' => ['status_id' => 'id']],
            [['treatments_id'], 'exist', 'skipOnError' => true, 'targetClass' => Treatments::className(), 'targetAttribute' => ['treatments_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'an' => Yii::t('app', 'An'),
            'treatments_id' => Yii::t('app', 'ยืมเพื่อ'),
            'created_by' => Yii::t('app', 'ผู้ยืม'),
            'created_at' => Yii::t('app', 'วันยืม'),
            'updated_by' => Yii::t('app', 'ผู้รับคืน'),
            'updated_at' => Yii::t('app', 'วันคืน'),
            'status_id' => Yii::t('app', 'สถานะ'),
            'day_want' => Yii::t('app', 'วันต้องการ'),
        ];
    }

    /**
     * Gets query for [[Status]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(Status::className(), ['id' => 'status_id']);
    }

    /**
     * Gets query for [[Treatments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTreatmentsBorrow()
    {
        return $this->hasOne(TreatmentsBorrow::className(), ['id' => 'treatments_id']);
    }
    public function getAn()
    {
        return $this->hasOne(IpdReg::className(), ['id' => 'treatments_id']);
    }
}
