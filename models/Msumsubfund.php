<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "m_sum_subfund".
 *
 * @property string $TRAN_ID
 * @property string $IPACC
 * @property string $HCODE
 * @property string $SUB_FUND
 * @property string $TOTL_AMT
 * @property string $TOTAL_RATE
 * @property string $PS_AF
 * @property string $ADJRW_NHSO
 * @property string $PRE_PAYMENT_RATE
 * @property string $ACT_AMT
 * @property string $PRE_PAYMENT_ACT_AMT
 * @property string $POINT
 * @property string $ITEM_CODE
 * @property string $ITEM_SOURCE
 * @property string $FUND_AF
 * @property string $FUND_TYPE
 * @property string $CANCEL_PAYMENT
 * @property string $CHARGE_RULE
 * @property string $EXPENSE_AMT
 * @property int $ITEM_REQ_QTY
 * @property int $ITEM_PAY_QTY
 * @property string $DENY
 */
class Msumsubfund extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'm_sum_subfund';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['TOTL_AMT', 'TOTAL_RATE', 'PRE_PAYMENT_RATE', 'ACT_AMT', 'PRE_PAYMENT_ACT_AMT', 'POINT', 'CHARGE_RULE', 'EXPENSE_AMT'], 'number'],
            [['ITEM_REQ_QTY', 'ITEM_PAY_QTY'], 'integer'],
            [['TRAN_ID'], 'string', 'max' => 20],
            [['IPACC', 'HCODE'], 'string', 'max' => 10],
            [['SUB_FUND'], 'string', 'max' => 25],
            [['PS_AF'], 'string', 'max' => 2],
            [['ADJRW_NHSO', 'ITEM_SOURCE'], 'string', 'max' => 50],
            [['ITEM_CODE'], 'string', 'max' => 255],
            [['FUND_AF', 'FUND_TYPE', 'DENY'], 'string', 'max' => 100],
            [['CANCEL_PAYMENT'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'TRAN_ID' => 'Tran  ID',
            'IPACC' => 'Ipacc',
            'HCODE' => 'Hcode',
            'SUB_FUND' => 'Sub  Fund',
            'TOTL_AMT' => 'Totl  Amt',
            'TOTAL_RATE' => 'Total  Rate',
            'PS_AF' => 'Ps  Af',
            'ADJRW_NHSO' => 'Adjrw  Nhso',
            'PRE_PAYMENT_RATE' => 'Pre  Payment  Rate',
            'ACT_AMT' => 'Act  Amt',
            'PRE_PAYMENT_ACT_AMT' => 'Pre  Payment  Act  Amt',
            'POINT' => 'Point',
            'ITEM_CODE' => 'Item  Code',
            'ITEM_SOURCE' => 'Item  Source',
            'FUND_AF' => 'Fund  Af',
            'FUND_TYPE' => 'Fund  Type',
            'CANCEL_PAYMENT' => 'Cancel  Payment',
            'CHARGE_RULE' => 'Charge  Rule',
            'EXPENSE_AMT' => 'Expense  Amt',
            'ITEM_REQ_QTY' => 'Item  Req  Qty',
            'ITEM_PAY_QTY' => 'Item  Pay  Qty',
            'DENY' => 'Deny',
        ];
    }
}
