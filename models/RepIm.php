<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rep_import".
 *
 * @property int $auto_id
 * @property string $rep เลขrep
 * @property string|null $id
 * @property string $train_id
 * @property string|null $hn
 * @property string|null $an
 * @property string|null $pid
 * @property string|null $fullname ชื่อสกุล
 * @property string|null $main กองทุน
 * @property string|null $regdate วันที่รับรักษา
 * @property string|null $discharge วันจำหน่าย
 * @property string|null $ins ค่ารักษา
 * @property string|null $pp
 * @property string|null $errorcode
 * @property string|null $sub กองทุนย่อย
 */
class RepIm extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rep_import';
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
            [['train_id'], 'required'],
            [['rep', 'id', 'train_id', 'hn', 'an', 'pid', 'fullname', 'main', 'regdate', 'discharge', 'ins', 'pp', 'errorcode', 'sub'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'auto_id' => 'Auto ID',
            'rep' => 'Rep',
            'id' => 'ID',
            'train_id' => 'Train ID',
            'hn' => 'Hn',
            'an' => 'An',
            'pid' => 'Pid',
            'fullname' => 'Fullname',
            'main' => 'Main',
            'regdate' => 'Regdate',
            'discharge' => 'Discharge',
            'ins' => 'Ins',
            'pp' => 'Pp',
            'errorcode' => 'Errorcode',
            'sub' => 'Sub',
        ];
    }
}
