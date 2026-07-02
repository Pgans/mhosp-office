<?php

namespace app\models;

use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use Yii;

/**
 * This is the model class for table "permits".
 *
 * @property int $id
 * @property string $AN
 * @property string $HN
 * @property string $fullname ชื่อผู้ป่วย
 * @property int $treatments_id
 * @property int $created_by ผู้ยืม
 * @property string $created_at วันที่ยืม
 * @property int $updated_by ผู้รับคืน
 * @property string $updated_at วันที่คืน
 * @property int $status_id
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
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'value' => new Expression('NOW()'),
            ],
            BlameableBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['HN', 'fullname', 'treatments_id'], 'required'],
            [['treatments_id', 'created_by', 'updated_by', 'status_id'], 'integer'],
            [['created_at', 'updated_at', 'day_want'], 'safe'],
            [['AN', 'HN'], 'string', 'max' => 7],
            [['fullname'], 'string', 'max' => 150],
            [['status_id'], 'exist', 'skipOnError' => true,
                'targetClass' => Status::className(),
                'targetAttribute' => ['status_id' => 'id']],
            [['treatments_id'], 'exist', 'skipOnError' => true,
                'targetClass' => Treatments::className(),
                'targetAttribute' => ['treatments_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'            => 'ID',
            'AN'            => 'AN',
            'HN'            => 'HN',
            'fullname'      => 'ชื่อผู้ป่วย',
            'treatments_id' => 'เพื่อ',
            'day_want'      => 'วันที่ต้องการ',
            'created_by'    => 'ผู้ยืม',
            'created_at'    => 'วันที่ยืม',
            'updated_by'    => 'ผู้รับคืน',
            'updated_at'    => 'วันที่คืน',
            'status_id'     => 'สถานะ',
        ];
    }

    // ──────────────────────────────────────────
    // Relations — เดิม (View ใช้ได้เหมือนเดิม)
    // ──────────────────────────────────────────

    /**
     * ผู้ยืม: ใช้ผ่าน user.cid → person.cid
     * View ยังใช้ $model->createdBy->firstname ได้เหมือนเดิม
     */
    public function getCreatedBy()
    {
        return $this->hasOne(Person::className(), ['cid' => 'cid'])
                    ->viaTable('user', ['id' => 'created_by']);
    }

    /**
     * ผู้รับคืน: ใช้ผ่าน user.cid → person.cid
     * View ยังใช้ $model->updater->firstname ได้เหมือนเดิม
     */
    public function getUpdater()
    {
        return $this->hasOne(Person::className(), ['cid' => 'cid'])
                    ->viaTable('user', ['id' => 'updated_by']);
    }

    /**
     * Status
     */
    public function getStatus()
    {
        return $this->hasOne(Status::className(), ['id' => 'status_id']);
    }

    /**
     * Treatments
     */
    public function getTreatments()
    {
        return $this->hasOne(Treatments::className(), ['id' => 'treatments_id']);
    }

    // ──────────────────────────────────────────
    // Relations — ใหม่ (ใช้ใน sendTelegram)
    // ──────────────────────────────────────────

    /**
     * ดึง User ของผู้ยืม
     */
    public function getCreatedByUser()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * ดึง User ของผู้รับคืน
     */
    public function getUpdaterUser()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    // ──────────────────────────────────────────
    // Helper: ดึงชื่อ รองรับทุก login path
    // ──────────────────────────────────────────

    /**
     * Flow: user.cid → person.cid → firstname + lastname
     *
     * @param string $type 'created' = ผู้ยืม, 'updated' = ผู้รับคืน
     * @return string
     */
    public function getPersonName($type = 'created')
    {
        $user = ($type === 'updated')
            ? $this->updaterUser
            : $this->createdByUser;

        if ($user === null) {
            return 'ไม่ทราบ';
        }

        if (!empty($user->cid)) {
            $person = Person::findOne(['cid' => $user->cid]);
            if ($person !== null) {
                return trim("{$person->firstname} {$person->lastname}");
            }
        }

        return $user->username ?? 'ไม่ทราบ';
    }

    /**
     * Shortcut: ชื่อผู้ยืม — ใช้ใน sendTelegram()
     */
    public function getBorrowerName()
    {
        return $this->getPersonName('created');
    }

    /**
     * Shortcut: ชื่อผู้รับคืน
     */
    public function getReceiverName()
    {
        return $this->getPersonName('updated');
    }
}