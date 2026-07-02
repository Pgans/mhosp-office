<?php

namespace app\models;

use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%meeting_agenda}}".
 *
 * @property int $id
 * @property string $title หัวข้อการประชุม
 * @property string $attime ครั้งที่
 * @property string $date วันที่
 * @property string $time เวลา
 * @property string $user ผู้จัด
 * @property string $create_date วันบันทึก
 */
class Meetingagenda extends \yii\db\ActiveRecord
{
    public $topic;
    public $description;
    public $agenda_id;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%meeting_agenda}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date', 'time', 'create_date'], 'safe'],
            [['title', 'attime'], 'string', 'max' => 255],
            [['user'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'attime' => 'Attime',
            'date' => 'Date',
            'time' => 'Time',
            'user' => 'User',
            'create_date' => 'Create Date',
        ];
    }
    public function getAgendaItems()
    {
        return $this->hasMany(Agendaitem::className(), ['meeting_agenda_id' => 'id']);
    }
//     public function getAgendaItems()
// {
//     return $this->hasMany(AgendaItem::class, ['meeting_agenda_id' => 'id']);
// }

public function getAgendasub()
{
    return $this->hasMany(Agendasubx::class, ['meeting_id' => 'id']);
}

// public function getUploadfiles()
// {
//     return $this->hasMany(Uploadfile::class, ['meeting_id' => 'id']);
// }
}
