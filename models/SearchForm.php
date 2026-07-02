<?php 
// models/SearchForm.php

namespace app\models;

use yii\base\Model;

class SearchForm extends Model
{
    public $cid;
    public $start_date;
    public $end_date;
    public $orther = [];

    public function rules()
    {
        return [
            [['cid'], 'string'],
            [['start_date', 'end_date'], 'date', 'format' => 'php:Y-m-d'],
            [['orther'], 'each', 'rule' => ['in', 'range' => [1, 2, 3]]],
        ];
    }

    public function attributeLabels()
    {
        return [
            'cid' => 'CID',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
            'orther' => 'Years',
        ];
    }
}


?>