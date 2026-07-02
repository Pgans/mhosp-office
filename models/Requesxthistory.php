<?php
namespace app\models;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "request_history".
 *
 * @property int $id รหัสยืม
 * @property string $no เลขที่หนังสือ
 * @property string $cid เลขประชาชน
 * @property string $hn เลขโรงพยาบาล
 * @property string $fullname ชื่อผู้ป่วย
 * @property int $assemble_id เพื่อ
 * @property int $created_by ผู้ยืม
 * @property string $created_at วันที่ยืม
 * @property int $updated_by ผู้รับคืน
 * @property string $updated_at วันที่คืน
 * @property int $status_id สถานะ
 * @property string $day_want วันที่ต้องการ
 */
class Requesxthistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'request_history';
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
            [['assemble_id', 'created_by', 'updated_by', 'status_id'], 'integer'],
            [['created_at', 'updated_at', 'day_want', 'start_date', 'end_date'], 'safe'],
            [['no'], 'string', 'max' => 20],
            [['cid'], 'string', 'max' => 13],
            [['hn'], 'string', 'max' => 7],
            [['fullname', 'orther'], 'string', 'max' => 150],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'         => Yii::t('app', 'รหัส'),
            'no'         => Yii::t('app', 'เลขที่หนังสือ'),
            'cid'        => Yii::t('app', 'เลขประชาชน'),
            'hn'         => Yii::t('app', 'HN'),
            'fullname'   => Yii::t('app', 'ชื่อผู้ป่วย'),
            'assemble'   => Yii::t('app', 'เพื่อประกอบ'),
            'created_by' => Yii::t('app', 'ผู้บันทึก'),
            'created_at' => Yii::t('app', 'วันที่ขอ'),
            'updated_by' => Yii::t('app', 'ผู้พิมพ์ประวัติ'),
            'updated_at' => Yii::t('app', 'วันที่พิมพ์'),
            'status_id'  => Yii::t('app', 'สถานะ'),
            'day_want'   => Yii::t('app', 'วันที่ส่งมอบ'),
            'start_date' => Yii::t('app', 'วันเข้ารักษา'),
            'end_date'   => Yii::t('app', 'วันจำหน่าย'),
            'orther'     => Yii::t('app', 'พิมพ์ย้อนหลัง'),
        ];
    }

    // ──────────────────────────────────────────
    // Relations — เดิม (View ใช้ได้เหมือนเดิม)
    // ──────────────────────────────────────────

    /**
     * ผู้บันทึก: ผ่าน user.cid → person.cid
     * View ยังใช้ $model->createdBy->firstname ได้เหมือนเดิม
     */
    public function getCreatedBy()
    {
        return $this->hasOne(Person::className(), ['cid' => 'cid'])
                    ->viaTable('user', ['id' => 'created_by']);
    }

    /**
     * ผู้พิมพ์ประวัติ: ผ่าน user.cid → person.cid
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
        return $this->hasOne(StatusHistory::className(), ['id' => 'status_id']);
    }

    /**
     * Assemble
     */
    public function getAssemble()
    {
        return $this->hasOne(Assemble::className(), ['id' => 'assemble_id']);
    }

    // ──────────────────────────────────────────
    // Relations — ใหม่ (ใช้ใน sendTelegram / helper)
    // ──────────────────────────────────────────

    /**
     * ดึง User ของผู้บันทึก
     */
    public function getCreatedByUser()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * ดึง User ของผู้พิมพ์ประวัติ
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
     * @param string $type 'created' = ผู้บันทึก, 'updated' = ผู้พิมพ์ประวัติ
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
     * Shortcut: ชื่อผู้บันทึก
     */
    public function getBorrowerName()
    {
        return $this->getPersonName('created');
    }

    /**
     * Shortcut: ชื่อผู้พิมพ์ประวัติ
     */
    public function getReceiverName()
    {
        return $this->getPersonName('updated');
    }
}