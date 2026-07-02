<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "rep_import".
 *
 * @property int $auto_id
 * @property string $rep เลขrep
 * @property string $id
 * @property string $train_id
 * @property string $hn
 * @property string $an
 * @property string $pid
 * @property string $fullname ชื่อสกุล
 * @property string $main กองทุน
 * @property string $regdate วันที่รับรักษา
 * @property string $discharge วันจำหน่าย
 * @property string $ins ค่ารักษา
 * @property string $pp
 * @property string $errorcode
 * @property string $sub กองทุนย่อย
 */
class Repimport extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rep_import';
    } 
	public $uploadPath = 'uploads/file';
	public $file;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
		   [['file'], 'file', 'extensions' => 'xls,xlsx', 'skipOnEmpty' => true],
            [['rep', 'id', 'train_id', 'hn', 'an', 'pid', 'fullname', 'main', 'regdate', 'discharge', 'ins', 'pp', 'errorcode', 'sub'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
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
	public function uploadFile($model, $attribute)
    {
        $file = UploadedFile::getInstance($model, $attribute);

        if($file){
            if($this->isNewRecord){
                $fileName = time().'_'.$file->baseName.'.'.$file->extension;
            }else{
                $fileName = $this->getOldAttribute($attribute);
            }
            $file->saveAs(Yii::getAlias('@webroot').'/'.$this->uploadPath.'/'.$fileName);

            return $fileName;
        }
        return $this->isNewRecord ? false : $this->getOldAttribute($attribute);
    }
}
