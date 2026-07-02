<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "room".
 *
 * @property int $room_id
 * @property string $room_name ชื่อห้อง
 * @property string $room_size ขนาดห้อง
 * @property string $room_seate จำนวนที่นั่ง
 * @property string $room_description รายละเอียดห้อง
 * @property string $room_img รูปห้อง
 * @property string $is_cancel
 */
class Room extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'room';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['room_name'], 'required'],
            [['room_description', 'is_cancel'], 'string'],
            [['room_name'], 'string', 'max' => 255],
            [['room_size', 'room_seate'], 'string', 'max' => 20],
            [['room_img'], 'string', 'max' => 150],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'room_id' => Yii::t('app', 'Room ID'),
            'room_name' => Yii::t('app', 'ชื่อห้อง'),
            'room_size' => Yii::t('app', 'ขนาดห้อง'),
            'room_seate' => Yii::t('app', 'จำนวนที่นั่ง'),
            'room_description' => Yii::t('app', 'รายละเอียดห้อง'),
            'room_img' => Yii::t('app', 'รูปห้อง'),
            'is_cancel' => Yii::t('app', 'Is Cancel'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return RoomQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new RoomQuery(get_called_class());
    }
}
