<?php

namespace app\models;

use Yii;

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class FdhToken extends ActiveRecord
{
    public static function tableName()
    {
        return 'fdh_token';
    }

    public function rules()
    {
        return [
            [['token_dt', 'token','staff_id'], 'safe'], // แก้ตามคอลัมน์ของคุณ
        ];
    }
}
