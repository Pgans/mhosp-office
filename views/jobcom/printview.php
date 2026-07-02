<?php

use Symfony\Component\CssSelector\Parser\Handler\WhitespaceHandler;
use yii\helpers\Html;
use app\models\Person;
use app\models\Jobcom;

$sendby = $model->send_by;
$detail = $model->detail;
//$lastname = $model->user->lastname;
$repairby = $model->repair_by->firstname;
//$lastname1 = $model->updater->lastname;
//$positions_id = $model->user->positions_id; 

 
// $departments = $model->person->dep_id;
// if ($department_id == '32') {
//     $department = 'xxx';
// } elseif ($department_id == '1') {
//     $department = ' B';
// } else {
//     $department = ' ไม่ระบุ ';
// }

//$destination = $model->destination;
//$passenger = $model->passenger;
//$description = $model->description;
//$datestart = $model->date_start;
//$dateend = $model->date_end;
//$license = $model->sendby;
$d = date("d", time());
$y = date("Y", time()) + 543;
$m = date("m", time());

if ($m == '01') {
    $trans_m = 'มกราคม';
} elseif ($m == '02') {
    $trans_m = 'กุมภาพันธ์';
} elseif ($m == '03') {
    $trans_m = 'มีนาคม';
} elseif ($m == '04') {
    $trans_m = 'เมษายน';
} elseif ($m == '05') {
    $trans_m = 'พฤษภาคม';
} elseif ($m == '06') {
    $trans_m = 'มิถุนายน';
} elseif ($m == '07') {
    $trans_m = 'กรกฎาคม';
} elseif ($m == '08') {
    $trans_m = 'สิงหาคม';
} elseif ($m == '09') {
    $trans_m = 'กันยายน';
} elseif ($m == '10') {
    $trans_m = 'ตุลาคม';
} elseif ($m == '11') {
    $trans_m = 'พฤศจิกายน';
} else {
    $trans_m = 'ธันวาคม';
}
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<html>
    <body>
        <div style="text-align:center;">
        
            <h4>แบบฟอร์มแจ้งซ่อมอุปกรณ์คอมพิวเตอร์ โรงพยาบาลม่วงสามสิบ </h4>
        </div>
        <div style="text-align:right;">
            <br>วันที่<?= Html::encode(' ' . $d) ?> เดือน<?= Html::encode(' ' . $trans_m) ?> พ.ศ. <?= Html::encode(' ' . $y) ?>
        </div>
        <div style="text-align:justify;">
            <br><br>เรียน หัวหน้ากลุ่มงานพัฒนายุทธศาสตร์สาธารณสุข

            <div style="margin-left: 10px;">
    <br><br>    กลุ่มงาน/งาน............................................................................................. ขอแจ้งซ่อมอุปกรณ์คอมพิวเตอร์ดังนี้ 
</div>
<div style="margin-left: 5px;">
   1. อุปกรณ์ที่แจ้งซ่อม ............................................................หมายเลขครุภัณฑ์......................................................
2. อาการ <span style="border-bottom: 1px dotted;"><?= Html::encode(' ' . $detail) ?></span>



<p>
  3. อุปกรณ์ต่อพ่วงที่นำมาด้วย   ...............................................................................................................................
</p>
</div>

 <div style="text-align: right;">
    ลงชื่อ...........................................................ผู้แจ้งซ่อม
	</div>
	<p >
	 &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
	 &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
	 <span style="border-bottom: 1px dotted;"><?= Html::encode(' ' . $sendby) ?></span>
  </p>
    </div>
	 &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
	 &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
	 <span style="border-bottom: 1px dotted;">วันที่ <?= Html::encode(' ' . $d) ?>/ <?= Html::encode(' ' . $trans_m) ?>/ <?= Html::encode(' ' . $y) ?></span>

    



           
        </div>
        <small>
            <br><br><br>
            &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
            ....................................................................... ผู้ขออนุญาต
            <br>
            &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
            &emsp;&emsp;&emsp;&emsp;( <?= Html::encode(' ' . $firstname . ' ' . $lastname . ' ') ?>)
            <br><br><br>
            &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
            ....................................................................... หัวหน้าหน่วยงานผู้ขออนุญาต
            <br>
            &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
            (.......................................................................)
            <br><br><br>
            &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
            ....................................................................... ผู้มีอำนาจสั่งใช้รถ
            <br>
            &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
            (.......................................................................)
            <br><br><br><br><hr>
        </small>
        <br><br>
        <div style="text-align:center;">
            <h2><b>การรับ-ส่งรถก่อนและหลังใช้งาน</b></h2>
        </div>     
        <br>1.การรับรถ&emsp;&emsp; <img src="images/4953048-200.png/"> ปกติ &emsp;&emsp; <img src="images/4953048-200.png/"> ผิดปกติ...............................................................................................................
        <br>&emsp;ก่อนใช้งาน   &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;  ผู้ส่ง...........................................................   ผู้รับ...........................................................
        <br>2.การส่งรถ&emsp;&emsp; <img src="images/4953048-200.png/"> ปกติ &emsp;&emsp; <img src="images/4953048-200.png/"> ผิดปกติ...............................................................................................................
        <br>&emsp;หลังใช้งาน   &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;  ผู้ส่ง...........................................................   ผู้รับ...........................................................
        <br>หมายเหตุ::ก่อนรับรถทุกครั้งให้ตรวจสอบสภาพรถและอุปกรณ์ประจำรถโดยละเอียด
        </small>
    </body> 
</html>
