<?php

use yii\helpers\Url;
use yii\web\View;
use app\assets\FullCalendarAsset;

FullCalendarAsset::register($this);

$js = <<<JS
    $(document).ready(function() {
        $('#calendar').fullCalendar({
            events: {
                url: Url::to(['calendar/get-events']), // ดึงกิจกรรมจากคอนโทรลเลอร์
                type: 'GET'
            },
            eventClick: function(event) {
                // ลิงก์ไปยังหน้าแก้ไขกิจกรรม
                window.location.href = Url::to(['calendar/update', 'id' => event.id]);
            }
        });
    });
JS;

$this->registerJs($js, View::POS_READY); // ลงทะเบียน JavaScript

?>

<div>
    <h2>ปฏิทินกิจกรรม</h2>
    <div id="calendar"></div> <!-- แสดงปฏิทิน -->
</div>
