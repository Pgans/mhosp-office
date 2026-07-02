<?php

namespace app\assets;

use yii\web\AssetBundle;

class FullCalendarAsset extends AssetBundle
{
    public $sourcePath = '@bower/fullcalendar/dist'; // ตำแหน่งของ FullCalendar
    public $css = [
        'fullcalendar.css',
    ];
    public $js = [
        'fullcalendar.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
