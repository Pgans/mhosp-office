<?php
namespace app\models;
use yii\base\Model;
use yii\web\UploadedFile;
/**
* UploadForm is the model behind the upload form.
*/
class UploadCSV extends Model
{
/**
* @var UploadedFile|Null file attribute
*/
//public $uploadPath = 'uploads/csv';
public $file;
/**
* @return array the validation rules.
*/
public function rules()
{
return [
[['file'], 'file'],
];
}

    public function attributeLabels()
    {
        return [
            'file' => ' ไฟล์ข้อมูล(csv)',

        ];
    }

}

?>