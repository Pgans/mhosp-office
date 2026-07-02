<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "booking_status".
 *
 * @property int $booking_status_id
 * @property string $booking_status_name สถานะการจอง
 * @property string $booking_statust_color สี
 */
class Bookingstatus extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'booking_status';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['booking_status_name'], 'required'],
            [['booking_status_name'], 'string', 'max' => 150],
            [['booking_statust_color'], 'string', 'max' => 45],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'booking_status_id' => Yii::t('app', 'Booking Status ID'),
            'booking_status_name' => Yii::t('app', 'สถานะการจอง'),
            'booking_statust_color' => Yii::t('app', 'สี'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return BookingStatusQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new BookingStatusQuery(get_called_class());
    }
}
