<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%agenda_subx}}".
 *
 * @property int $sub_id
 * @property int $meeting_id
 * @property int $agenda_id
 * @property string $sub_topic หัวข้อย่อย
 * @property string $sub_description รายละเอียดการนำเสนอ
 * @property string $department แผนก
 * @property string $filename ชื่อไฟล์
 * @property string $path uploads
 * @property string $create_date วันบันทึก
 *
 * @property AgendaItem $agenda
 */
class Agendasubx extends \yii\db\ActiveRecord
{
    public $file; // ตัวแปรสำหรับเก็บไฟล์ที่อัปโหลด
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%agenda_subx}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['meeting_id', 'agenda_id',], 'integer'],
            [['sub_description'], 'string'],
            [['create_date'], 'safe'],
            [['sub_topic', 'department', 'path'], 'string', 'max' => 255],
            [['filename'], 'string', 'max' => 255],
            [['file'], 'file', 'skipOnEmpty' => true], // กฎสำหรับไฟล์
            //[['agenda_id'], 'exist', 'skipOnError' => true, 'targetClass' => AgendaItem::className(), 'targetAttribute' => ['agenda_id' => 'agen_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'sub_id' => 'Sub ID',
            'meeting_id' => 'Meeting ID',
            'agenda_id' => 'Agenda ID',
			'agen_id' => 'Agen ID',
            'sub_topic' => 'Sub Topic',
            'sub_description' => 'Sub Description',
            'department' => 'Department',
            'filename' => 'Filename',
            'path' => 'Path',
            'create_date' => 'Create Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAgendaItems()
    {
        return $this->hasOne(AgendaItem::className(), ['agenda_id' => 'agen_id']);
    }
    public function getMeetingAgends()
    {
        return $this->hasOne(Meetingagenda::className(), ['meeting_id' => 'meeting_id']);
    }
   
}
