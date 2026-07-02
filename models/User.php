<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property int $status
 * @property string $level_user สิทธิ์
 * @property int $created_at
 * @property int $updated_at
 * @property string $unconfirmed_email
 * @property string $registration_ip
 * @property int $blocked_at
 * @property int $flags
 * @property int $last_login_at
 * @property int $confirmed_at
 *
 * @property History[] $histories
 * @property Person[] $people
 */
class User extends \yii\db\ActiveRecord
{
    public $firstname;
    public $lastname;

    public static function tableName()
    {
        return 'user';
    }
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
 
    /* เพิ่มเติม const ลงไป */
    const ROLE_USER = 'USER';
    const ROLE_EMPLOYEE = 'EMPLOYEE';
    const ROLE_ADMIN = 'ADMIN';
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'auth_key', 'password_hash', 'email', 'level_user', 'created_at', 'updated_at'], 'required'],
            [['status', 'created_at', 'updated_at', 'blocked_at', 'flags', 'last_login_at', 'confirmed_at'], 'integer'],
            [['level_user'], 'string'],
            [['username', 'password_hash', 'password_reset_token', 'email', 'unconfirmed_email'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['registration_ip'], 'string', 'max' => 45],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['password_reset_token'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'email' => 'Email',
            'status' => 'Status',
            'level_user' => 'Level User',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'unconfirmed_email' => 'Unconfirmed Email',
            'registration_ip' => 'Registration Ip',
            'blocked_at' => 'Blocked At',
            'flags' => 'Flags',
            'last_login_at' => 'Last Login At',
            'confirmed_at' => 'Confirmed At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHistories()
    {
        return $this->hasMany(History::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPeople()
    {
        return $this->hasMany(Person::className(), ['user_id' => 'id']);
    }
	public function getPerson()
    {
        return $this->hasOne(Person::class, ['user_id' => 'id']);
    }
}
