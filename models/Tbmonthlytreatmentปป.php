<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%tb_monthly_treatment}}".
 *
 * @property int $id
 * @property string $hn
 * @property string $start_month
 * @property string $month2
 * @property string $month3
 * @property string $month4
 * @property string $month5
 * @property string $month6
 * @property string $month7
 * @property string $treatment_detail
 * @property string $created_at
 */
class Tbmonthlytreatment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%tb_monthly_treatment}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['start_month', 'month2', 'month3', 'month4', 'month5', 'month6', 'month7', 'created_at'], 'safe'],
            [['hn'], 'string', 'max' => 10],
            [['treatment_detail'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'hn' => Yii::t('app', 'Hn'),
            'start_month' => Yii::t('app', 'Start Month'),
            'month2' => Yii::t('app', 'Month 2'),
            'month3' => Yii::t('app', 'Month 3'),
            'month4' => Yii::t('app', 'Month 4'),
            'month5' => Yii::t('app', 'Month 5'),
            'month6' => Yii::t('app', 'Month 6'),
            'month7' => Yii::t('app', 'Month 7'),
            'treatment_detail' => Yii::t('app', 'Treatment Detail'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }
}
