<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%log_datacenter}}".
 *
 * @property int $id
 * @property string $username
 * @property string $ip
 * @property string $patient_cid
 * @property string $datetime
 */
class LogDatacenter extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%log_datacenter}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['datetime'], 'safe'],
            [['username', 'ip', 'patient_cid'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'username' => Yii::t('app', 'Username'),
            'ip' => Yii::t('app', 'Ip'),
            'patient_cid' => Yii::t('app', 'Patient Cid'),
            'datetime' => Yii::t('app', 'Datetime'),
			'dep' => Yii::t('app', 'Department'), 
        ];
    }
}
