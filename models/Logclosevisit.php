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
 * @property string $users ผู้ส่งข้อมูล
 * @property string $d_update วันที่ส่งข้อมูล
 */
class Logclosevisit extends \yii\db\ActiveRecord
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
            [['visit_id', 'd_update'], 'required'],
            [['d_update'], 'safe'],
            [['visit_id', 'pid', 'status'], 'string', 'max' => 50],
            [['messagecode'], 'string', 'max' => 30],
            [['response'], 'string', 'max' => 255],
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
            'users' => Yii::t('app', 'Users'),
            'd_update' => Yii::t('app', 'D Update'),
        ];
    }
}
