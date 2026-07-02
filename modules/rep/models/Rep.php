<?php

namespace app\modules\rep\models;

use Yii;

/**
 * This is the model class for table "rep".
 *
 * @property int $ID
 * @property string|null $REP
 * @property string|null $SEQ
 * @property string|null $TRAN_ID
 * @property string|null $HN
 * @property string|null $AN
 * @property string|null $PID
 * @property string|null $FULLNAME
 * @property string|null $INSCL
 * @property string|null $REGDATE
 * @property string|null $COMPEN
 * @property string|null $INST
 */
class Rep extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rep';
    }
     /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db1');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['REGDATE'], 'safe'],
            [['REP'], 'string', 'max' => 20],
            [['SEQ', 'TRAN_ID', 'HN', 'AN', 'PID', 'FULLNAME', 'INSCL', 'COMPEN', 'INST'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'REP' => 'Rep',
            'SEQ' => 'Seq',
            'TRAN_ID' => 'Tran ID',
            'HN' => 'Hn',
            'AN' => 'An',
            'PID' => 'Pid',
            'FULLNAME' => 'Fullname',
            'INSCL' => 'Inscl',
            'REGDATE' => 'Regdate',
            'COMPEN' => 'Compen',
            'INST' => 'Inst',
        ];
    }
}
