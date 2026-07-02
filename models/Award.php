<?php

namespace app\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\Json;
use \yii\web\UploadedFile;

/**
 * This is the model class for table "award".
 *
 * @property integer $id
 * @property string $ref
 * @property string $title
 * @property string $covenant
 * @property string $docs
 * @property string $create_date
 */
class award extends \yii\db\ActiveRecord
{
    const UPLOAD_FOLDER = 'award';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'award';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['create_date'], 'safe'],
            [['ref'], 'string', 'max' => 50],
            [['title'], 'string', 'max' => 255],
            [['covenant'],'file','maxFiles'=>1],
            [['name', 'surname'], 'required'],
            [['name', 'surname'], 'string', 'max' => 100],
             [['photo'], 'file',
          'skipOnEmpty' => true,
          'extensions' => 'png,jpg'
        ]
    ];
}
            

    /**
     * @inheritdoc
     */
     public function attributeLabels()
    {
        return [
          'id' => 'ID',
          'ref' => 'หลายเลข referent สำหรับอัพโหลดไฟล์ ajax',
          'title' => 'หัวข้อ',
          'name' =>'ชื่อรางวัล',
          'surname' => 'รายละเอียด',
          'photo' =>'รูปภาพ',
          'covenant' => 'ดาวน์โหลดไฟล์',
          'create_date' => 'วันที่อัพโหลด',
        ];
    }
    public function upload($model,$attribute)
{
    $photo  = UploadedFile::getInstance($model, $attribute);
      $path = $this->getUploadPath();
    if ($this->validate() && $photo !== null) {

        $fileName = md5($photo->baseName.time()) . '.' . $photo->extension;
        //$fileName = $photo->baseName . '.' . $photo->extension;
        if($photo->saveAs($path.$fileName)){
          return $fileName;
        }
    }
    return $model->isNewRecord ? false : $model->getOldAttribute($attribute);
}

    public static function getUploadPath(){
        return Yii::getAlias('@webroot').'/'.self::UPLOAD_FOLDER.'/';
    }

    public static function getUploadUrl(){
        return Url::base(true).'/'.self::UPLOAD_FOLDER.'/';
    }

    public function listDownloadFiles($type){
     $docs_file = '';
     if(in_array($type, ['covenant'])){
             $data = $type==='docs'?$this->docs:$this->covenant;
             $files = Json::decode($data);
            if(is_array($files)){
                 $docs_file ='<ul>';
                 foreach ($files as $key => $value) {
                    $docs_file .= '<li>'.Html::a($value,['/award/download','id'=>$this->id,'file'=>$key,'file_name'=>$value]).'</li>';
                 }
                 $docs_file .='</ul>';
            }
     }

     return $docs_file;
    }

    public function initialPreview($data,$field,$type='file'){
            $initial = [];
            $files = Json::decode($data);
            if(is_array($files)){
                 foreach ($files as $key => $value) {
                    if($type=='file'){
                        $initial[] = "<div class='file-preview-other'><h2><i class='glyphicon glyphicon-file'></i></h2></div>";
                    }elseif($type=='config'){
                        $initial[] = [
                            'caption'=> $value,
                            'width'  => '120px',
                            'url'    => Url::to(['/award/deletefile','id'=>$this->id,'fileName'=>$key,'field'=>$field]),
                            'key'    => $key
                        ];
                    }
                    else{
                        $initial[] = Html::img(self::getUploadUrl().$this->ref.'/'.$value,['class'=>'file-preview-image', 'alt'=>$model->file_name, 'title'=>$model->file_name]);
                    }
                 }
         }
        return $initial;
    }
    public function getPhotoViewer(){
        return empty($this->photo) ? Yii::getAlias('@web').'/img/none.png' : $this->getUploadUrl().$this->photo;
      }
    

}


