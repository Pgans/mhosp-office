<?php 
// models/SearchForm.php

namespace app\models;

use yii\base\Model;

class SearchFormx extends Model
{
    public $an;

    public function rules()
    {
        return [
            [['an'], 'required'],
            [['an'], 'string'],
        ];
    }
}


?>