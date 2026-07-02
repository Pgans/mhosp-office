<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use yii\helpers\ArrayHelper;
//

use yii2fullcalendar\yii2fullcalendar;

/* @var $this yii\web\View */
/* @var $searchModel app\models\OrdersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'ปฏิทินกิจกรรม';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="orders-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]);  ?>
	

    <p>
    <?= Html::a(
        '<i class="fa fa-plus"></i> เพิ่มการมาตรวจ',  // ข้อความและไอคอนของปุ่ม
        ['create'],  // ลิงก์ไปยัง 'create'
        [
            'class' => 'btn btn-info btn-lg',  // เพิ่มคลาส CSS
            'style' => 'box-shadow: 3px 3px 5px rgba(0, 0, 0, 0.3); background: linear-gradient(to bottom, #3c8dbc, #367fa9); border: none;'  // สไตล์สำหรับเอฟเฟกต์ 3D
        ]
    ) ?>
    <!-- ข้อความอธิบายเพิ่มเติม -->
    *บันทึกการได้รับการตรวจ หรือการนัดหมาย*
</p>


    <div class="box box-success box-solid">

        <div class="box-header">
            <div class="box-title"><i class="fa fa-calendar"></i> กิจกรรมการตรวจ นัดหมาย</div>
        </div>

        <div class="box-body">  
            <!-- 
            <p>
            <?= Html::a('เพิ่มการจอง', ['create'], ['class' => 'btn btn-success']) ?>
            </p>
            -->
            <?=
            yii2fullcalendar::widget([
                'options' => [
                    'lang' => 'th',
                ],
                'events' => $events,
                'id' => 'calendar',
            ]);
            ?>

        </div>
    </div>
</div>
<script>
$js = <<<JS
$(document).ready(function() {
    $('#calendar').fullCalendar({
        events: {
            url: Url::to(['calendar/get-events']),  // ใช้ URL สำหรับดึงกิจกรรม
            type: 'GET'
        },
        eventClick: function(event) {
            // เปิด URL ในหน้าต่างใหม่หรือไปยังหน้า view
            if (event.url) {
                window.location.href = event.url; // เปลี่ยนเส้นทางไปยัง URL เมื่อคลิก
            }
        },
    });
});
JS;

// ลงทะเบียน JavaScript สำหรับ FullCalendar
$this->registerJs($js, View::POS_READY);

</script>