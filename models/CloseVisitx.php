<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%log_closevisits}}".
 *
 * @property int $id
 * @property string $visit_id visit
 * @property string $pid
 * @property string $status สถานะการส่ง
 * @property string $messagecode รหัสสถานะการส่ง
 * @property string $response คืนค่าjson
 * @property string $transaction_uid
 * @property string $users ผู้ส่งข้อมูล
 * @property string $send_date วันที่ส่งข้อมูล
 */
class Closevisit extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%log_closevisits}}';
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
            [['visit_id', 'pid', 'status', 'messagecode', 'response', 'transaction_uid', 'users', 'send_date'], 'required'],
            [['send_date'], 'safe'],
            [['visit_id', 'pid', 'status'], 'string', 'max' => 50],
            [['messagecode'], 'string', 'max' => 30],
            [['response'], 'string', 'max' => 255],
            [['transaction_uid'], 'string', 'max' => 250],
            [['users'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'visit_id' => Yii::t('app', 'Visit ID'),
            'pid' => Yii::t('app', 'Pid'),
            'status' => Yii::t('app', 'Status'),
            'messagecode' => Yii::t('app', 'Messagecode'),
            'response' => Yii::t('app', 'Response'),
            'transaction_uid' => Yii::t('app', 'Transaction Uid'),
            'users' => Yii::t('app', 'Users'),
            'send_date' => Yii::t('app', 'Send Date'),
        ];
    }
}
