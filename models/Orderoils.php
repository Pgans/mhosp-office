<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\helpers\ArrayHelper;
use yii\db\Expression;
use yii\behaviors\AttributeBehavior;
use \yii\db\ActiveRecord;
use app\models\Skill;
use app\models\Oils;

/**
 * This is the model class for table "{{%order_oils}}".
 *
 * @property string $oilorder_id รหัสใบสั่งจ่ายน้ำมัน
 * @property string $created_at
 * @property string $fullname ชื่อสุกล
 * @property string $spray_id รหัสการพ่นควันfk
 * @property string $oils ชนิดน้ำมัน
 * @property string $diagnosis
 * @property string $province_id จังหวัด
 * @property string $amphur_id อำเภอ
 * @property string $district_id ตำบล
 * @property string $mooban_id หมู่บ้าน
 * @property string $anamai_id รหัสอนามัย
 * @property int $created_by ผู้บันทึก
 * @property int $updated_by ผู้แก้ไข
 * @property string $d_update วันที่แก้ไขสุดท้าย
 *
 * @property Province $province
 * @property Spray $spray
 * @property HospAnamai $anamai
 * @property Mooban $mooban
 * @property District $district
 * @property Amphur $amphur
 */
class Orderoils extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return '{{%order_oils}}';
    }
    public function behaviors()
{
    return [
        [
            'class' => AttributeBehavior::className(),
            'attributes' => [
                ActiveRecord::EVENT_BEFORE_INSERT => 'oils',
                ActiveRecord::EVENT_BEFORE_UPDATE => 'oils',
            ],
            'value' => function ($event) {
                return implode(',', $this->oils);
            },
        ],
        [
            'class' => TimestampBehavior::className(),
            'createdAtAttribute' => 'created_at',
            'updatedAtAttribute' => 'd_update',
            'value' => new Expression( 'NOW()' ),
        ],
        [
            'class' => BlameableBehavior::className(),
            'createdByAttribute' => 'created_by',
            'updatedByAttribute' => 'updated_by',
        ],
    ];
}

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'd_update', 'oils','skill', 'mooban_id'], 'safe'],
            [['fullname', 'spray_id', 'province_id', 'amphur_id', 'district_id', 'mooban_id', 'anamai_id'], 'required'],
            [['spray_id', 'province_id', 'amphur_id', 'district_id', 'anamai_id', 'created_by', 'updated_by'], 'integer'],
            [['fullname'], 'string', 'max' => 100],
            [['diagnosis'], 'string', 'max' => 200],
            [['province_id'], 'exist', 'skipOnError' => true, 'targetClass' => Province::className(), 'targetAttribute' => ['province_id' => 'PROVINCE_ID']],
            [['spray_id'], 'exist', 'skipOnError' => true, 'targetClass' => Spray::className(), 'targetAttribute' => ['spray_id' => 'spray_id']],
            [['anamai_id'], 'exist', 'skipOnError' => true, 'targetClass' => Hospanamai::className(), 'targetAttribute' => ['anamai_id' => 'anamai_id']],
            [['mooban_id'], 'exist', 'skipOnError' => true, 'targetClass' => Mooban::className(), 'targetAttribute' => ['mooban_id' => 'mooban_id']],
            [['district_id'], 'exist', 'skipOnError' => true, 'targetClass' => District::className(), 'targetAttribute' => ['district_id' => 'DISTRICT_ID']],
            [['amphur_id'], 'exist', 'skipOnError' => true, 'targetClass' => Amphur::className(), 'targetAttribute' => ['amphur_id' => 'AMPHUR_ID']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'oilorder_id' => 'รหัสใบสั่งจ่ายน้ำมัน',
            'created_at' => 'Created At',
            'fullname' => 'ชื่อสุกล',
            'spray_id' => 'รหัสการพ่นควันfk',
            'oils' => 'ชนิดน้ำมัน',
            'diagnosis' => 'Diagnosis',
            'province_id' => 'จังหวัด',
            'amphur_id' => 'อำเภอ',
            'district_id' => 'ตำบล',
            'mooban_id' => 'หมู่บ้าน',
            'anamai_id' => 'รหัสอนามัย',
            'created_by' => 'ผู้บันทึก',
            'updated_by' => 'ผู้แก้ไข',
            'd_update' => 'วันที่แก้ไขสุดท้าย',
            'skillName'=> 'น้ำมันพ่น',
        ];
    }
    

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProvince()
    {
        return $this->hasOne(Province::className(), ['PROVINCE_ID' => 'province_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(Person::className(), ['user_id' => 'created_by']);
    }
    public function getUpdater()
    {
        return $this->hasOne(Person::className(), ['user_id' => 'updated_by']);
    }


    public function getSpray()
    {
        return $this->hasOne(Spray::className(), ['spray_id' => 'spray_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAnamai()
    {
        return $this->hasOne(Hospanamai::className(), ['anamai_id' => 'anamai_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMooban()
    {
        return $this->hasOne(Mooban::className(), ['mooban_id' => 'mooban_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDistrict()
    {
        return $this->hasOne(District::className(), ['DISTRICT_ID' => 'district_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAmphur()
    {
        return $this->hasOne(Amphur::className(), ['AMPHUR_ID' => 'amphur_id']);
    }
    public function getCreater()
    {
        return $this->hasOne(Person::className(), ['user_id' => 'created_by']);
    }
    public function getOilsName(){
        //$skills = $this->getItemSkill();
        $skills = ArrayHelper::map(Skill::find()->all(),'id','name');
       // $skills = ArrayHelper::map(Oils::find()->all(),'oil_id','oil_name');
        $skillSelected = explode(',', $this->oils);
        $skillSelectedName = [];
        foreach ($skills as $key => $oilsName) {
          foreach ($skillSelected as $skillKey) {
            if($key === (int)$skillKey){
              $skillSelectedName[] = $oilsName;
            }
          }
        }
        return implode(', ', $skillSelectedName);
    }
    
    public function oilsToArray()
    {
      return $this->oils= explode(',', $this->oils);
    }


}
