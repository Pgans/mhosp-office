<?php 


// models/RequestHistory.php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class RequestHistory extends ActiveRecord
{
    public static function tableName()
    {
        return 'request_history';
    }

    // กำหนด rules และ attributes ตามที่ต้องการ

public function getCreatedBy()
    {
        return $this->hasOne(Person::className(), ['user_id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(StatusHistory::className(), ['id' => 'status_id']);
    }
    public function getPerson()
    {
        return $this->hasOne(Person::class, ['user_id' => 'user_id']);
    }
    public function getAssemble()
    {
        return $this->hasOne(Assemble::className(), ['id' => 'assemble_id']);
    }
    public function getUpdater()
    {
        return $this->hasOne(Person::className(), ['user_id' => 'updated_by']);
    }
}



?>