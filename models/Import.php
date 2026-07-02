<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;
use yii2\data\ArrayDataProvider;


/**
 * This is the model class for table "import".
 *
 * @property int $id
 * @property string $file
 * @property string $date
 */
class Import extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'import';
    }
    public $uploadPath = 'uploads/file';

    public function rules()
    {
        return [
            [['regdate'], 'safe'],
			[['file'], 'file', 'extensions' => 'xls,xlsx', 'skipOnEmpty' => true]
            //[['file'], 'string', 'max' => 150],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'file' => 'ไฟล์',
            'regdate' => 'บันทึกเมื่อ',
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
