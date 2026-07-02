<?php
namespace app\components;
use yii\base\Component;

class Rental extends Component
{
    public function getRentalStatus($status = null)
    {
        if($status == 0)
        {
            $text = '<span class="label label-warning">รออนุมัติ</span>';
        }
        else if($status == 1)
        {
            $text = '<span class="label label-success">อนุมัติ</span>';
        }
        else if($status == 2)
        {
            $text = '<span class="label label-danger">ไม่อนุมัติ</span>';
        }
        return $text;
    }
    public function getRentalArea($area = null)
    {
        if($area == 'I')
        {
            $text = '<span class="label label-primary">ในจังหวัด</span>';
        }
        else if($area == 'O')
        {
            $text = '<span class="label label-danger">นอกจังหวัด</span>';
        }
        
        return $text;
    }

}