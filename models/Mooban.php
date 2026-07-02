<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mooban".
 *
 * @property string $mooban_id
 * @property string $mooban_code รหัสหมู่บ้าน
 * @property string $mooban_name ชื่อหมูบ้าย
 * @property int $amphur_id
 * @property int $province_id
 * @property int $district_id
 * @property string $hospmain
 * @property string $hospsub
 *
 * @property OrderOils[] $orderOils
 */
class Mooban extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mooban';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['amphur_id', 'province_id', 'district_id'], 'required'],
            [['amphur_id', 'province_id', 'district_id'], 'integer'],
            [['mooban_code'], 'string', 'max' => 8],
            [['mooban_name'], 'string', 'max' => 150],
            [['hospmain', 'hospsub'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'mooban_id' => 'Mooban ID',
            'mooban_code' => 'Mooban Code',
            'mooban_name' => 'Mooban Name',
            'amphur_id' => 'Amphur ID',
            'province_id' => 'Province ID',
            'district_id' => 'District ID',
            'hospmain' => 'Hospmain',
            'hospsub' => 'Hospsub',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderOils()
    {
        return $this->hasMany(OrderOils::className(), ['mooban_id' => 'mooban_id']);
    }
}
