<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "rental".
 *
 * @property int $id
 * @property string $destination จุดหมาย
 * @property int $passenger จำนวนผู้โดยสาร
 * @property string $description รายละเอียด
 * @property string $date_start เวลาเริ่ม
 * @property string $date_end เวลาสิ้นสุด
 * @property string $create_at
 * @property string $update_at
 * @property string $status สถานะ
 * @property int $user_id ผู้จอง
 * @property int $vehicle_id
 * @property int $driver_id พนักงานขับรถ
 * @property int $updated_by ผู้แก้ไข
 * @property string $area พื้นที่
 */
class Rental extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rental';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class'              => TimestampBehavior::className(),
                'createdAtAttribute' => 'create_at',
                'updatedAtAttribute' => 'update_at',
                'value'              => new Expression('NOW()'),
            ],
            [
                'class'              => BlameableBehavior::className(),
                'createdByAttribute' => 'user_id',
                'updatedByAttribute' => 'updated_by',
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['destination', 'description', 'date_start', 'date_end'], 'required'],
            [['passenger', 'user_id', 'vehicle_id', 'driver_id', 'dep_id', 'updated_by'], 'integer'],
            [['description', 'status', 'area'], 'string'],
            [['date_start', 'date_end', 'create_at', 'update_at'], 'safe'],
            [['destination'], 'string', 'max' => 45],
            [['vehicle_id'], 'exist', 'skipOnError' => true,
                'targetClass' => Vehicle::className(),
                'targetAttribute' => ['vehicle_id' => 'vehicle_id']],
            [['driver_id'], 'exist', 'skipOnError' => true,
                'targetClass' => Drivers::className(),
                'targetAttribute' => ['driver_id' => 'driver_id']],
            [['dep_id'], 'exist', 'skipOnError' => true,
                'targetClass' => Departments::className(),
                'targetAttribute' => ['dep_id' => 'dep_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'          => Yii::t('app', 'ID'),
            'destination' => Yii::t('app', 'ไปที่'),
            'passenger'   => Yii::t('app', 'จำนวนคน'),
            'description' => Yii::t('app', 'รายละเอียด'),
            'date_start'  => Yii::t('app', 'เวลาเริ่ม'),
            'date_end'    => Yii::t('app', 'เวลาสิ้นสุด'),
            'create_at'   => Yii::t('app', 'วันที่บันทึกการจอง'),
            'update_at'   => Yii::t('app', 'Update At'),
            'status'      => Yii::t('app', 'สถานะ'),
            'user_id'     => Yii::t('app', 'ผู้จอง'),
            'vehicle_id'  => Yii::t('app', 'ทะเบียนรถ'),
            'driver_id'   => Yii::t('app', 'พนักงานขับรถ'),
            'updated_by'  => Yii::t('app', 'ผู้จัดการรถ'),
            'area'        => Yii::t('app', 'พื้นที่'),
            'dep_id'      => Yii::t('app', 'หน่วยงาน'),
        ];
    }

    // ──────────────────────────────────────────
    // Relations — เดิม (View ใช้ได้เหมือนเดิม)
    // ──────────────────────────────────────────

    /**
     * ผู้จอง: ผ่าน user.cid → person.cid
     * View ยังใช้ $model->user->firstname ได้เหมือนเดิม
     */
    public function getUser()
    {
        return $this->hasOne(Person::className(), ['cid' => 'cid'])
                    ->viaTable('user', ['id' => 'user_id']);
    }

    /**
     * ผู้จัดการรถ: ผ่าน user.cid → person.cid
     * View ยังใช้ $model->updater->firstname ได้เหมือนเดิม
     */
    public function getUpdater()
    {
        return $this->hasOne(Person::className(), ['cid' => 'cid'])
                    ->viaTable('user', ['id' => 'updated_by']);
    }

    /**
     * Vehicle
     */
    public function getVehicle()
    {
        return $this->hasOne(Vehicle::className(), ['vehicle_id' => 'vehicle_id']);
    }

    /**
     * Driver
     */
    public function getDriver()
    {
        return $this->hasOne(Drivers::className(), ['driver_id' => 'driver_id']);
    }

    /**
     * Departments
     */
    public function getDepartments()
    {
        return $this->hasOne(Departments::className(), ['dep_id' => 'dep_id']);
    }

    /**
     * Positions
     */
    public function getPositions()
    {
        return $this->hasOne(Positions::className(), ['id' => 'positions_id']);
    }

    // ──────────────────────────────────────────
    // Relations — ใหม่ (ใช้ใน sendTelegram / helper)
    // ──────────────────────────────────────────

    /**
     * ดึง User ของผู้จอง
     */
    public function getBookerUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * ดึง User ของผู้จัดการรถ
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
     * @param string $type 'booker' = ผู้จอง, 'updated' = ผู้จัดการรถ
     * @return string
     */
    public function getPersonName($type = 'booker')
    {
        $user = ($type === 'updated')
            ? $this->updaterUser
            : $this->bookerUser;

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
     * Shortcut: ชื่อผู้จอง — ใช้ใน sendTelegram()
     */
    public function getBookerName()
    {
        return $this->getPersonName('booker');
    }

    /**
     * Shortcut: ชื่อผู้จัดการรถ
     */
    public function getManagerName()
    {
        return $this->getPersonName('updated');
    }

    /**
     * @inheritdoc
     */
    public static function find()
    {
        return new RentalQuery(get_called_class());
    }
}