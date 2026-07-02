<?php

use Symfony\Component\CssSelector\Parser\Handler\WhitespaceHandler;
use yii\helpers\Html;
use app\models\Person;

$firstname = $model->user->firstname;
$lastname = $model->user->lastname;
$firstname1 = $model->updater->firstname;
$lastname1 = $model->updater->lastname;
$positions_id = $model->user->positions_id; 
 if ($positions_id == '1') {
     $positions = 'นายแพทย์';
 } elseif ($positions_id == '2') {
     $positions = 'พยาบาลวิชาชีพ';
 } elseif ($positions_id == '3') {
     $positions = 'พยาบาลเทคนิค';
 } elseif ($positions_id == '4') {
     $positions = 'เจ้าหน้าที่รังสีการแพทย์';
 } elseif ($positions_id == '5') {
     $positions = 'คนสวน';
 } elseif ($positions_id == '6') {
     $positions = 'พนักงานประจำตึก';
 } elseif ($positions_id == '7') {
     $positions = 'เภสัชกร';
 } elseif ($positions_id == '8') {
     $positions = 'เจ้าพนักงานเภสัชกรรม';
 } elseif ($positions == '9') {
     $positions = 'ทันตแพทย์';
 } elseif ($positions == '10') {
     $positions = 'ผู้ช่วยเหลือคนไข้';
 } elseif ($positions_id == '11') {
     $positions = 'จพ.วิทยาศาสตร์การแพทย์';
 } elseif ($positions_id== '12') {
     $positions = 'พนักงานเปล';
 } elseif ($positions_id == '13') {
     $positions = 'เจ้าพนักงานทันตสาธารณสุข';
 } elseif ($positions_id == '14') {
     $positions = 'หมอนวดแผนไทย';
 } elseif ($positions_id == '15') {
     $positions = 'อายุรเวช';
 } elseif ($positions_id == '16') {
     $positions = 'นักเทคนิคการแพทย์';
 } elseif ($positions_id == '17') {
     $positions = 'ผู้ช่วยทันตแพทย์';
 } elseif ($positions_id == '18') {
     $positions = 'เจ้าพนักงานธุรการ';
 } elseif ($positions_id == '19') {
     $positions = 'เจ้าพนักงานเวชสถิติ';
 } elseif ($positions_id == '20') {
     $positions = 'นักรังสีการแพทย์';
 } elseif ($positions_id == '21') {
     $positions = 'เจ้าหน้าที่บริหารงานทั่วไป';
 } elseif ($positions_id == '22') {
     $positions = 'เจ้าพนักงานพัสดุ';
 } elseif ($positions_id == '23') {
     $positions = 'นักวิชาการสาธารณสุข';
 } elseif ($positions == '24') {
     $positions = 'โภชนากร';
 } elseif ($positions_id == '25') {
     $positions = 'เจ้าหน้าที่บริหารงานสาธารณสุข';
 } elseif ($positions_id == '26') {
     $positions = 'พยาบาลวิชาชีพ (วิสัญญี)';
 } elseif ($positions_id == '27') {
     $positions = 'ช่างเทคนิค';
 } elseif ($positions_id == '28') {
     $positions = 'ลูกมือช่าง';
 } elseif ($positions_id == '29') {
     $positions = 'พนักงานขับรถ';
 } elseif ($positions_id == '30') {
     $positions = 'เจ้าหน้าที่ธุรการ';
 } elseif ($positions_id == '31') {
     $positions = 'นักกายภาพบำบัด';
 } elseif ($positions == '32') {
     $positions = 'นักวิทยาศาสตร์การแพทย์';
 } elseif ($positions_id == '33') {
     $positions = 'เจ้าพนักงานสาธารณสุขชุมชน(เวชกิจฉุกเฉิน)';
 } elseif ($positions_id == '34') {
     $positions = 'กู้ชีพฉุกเฉิน';
 } elseif ($positions == '35') {
     $positions = 'เจ้าพนักงานการเงินและบัญชี';
 } elseif ($positions == '36') {
     $positions = 'พนักงานตรวจทานข้อมูล';
 } elseif ($positions_id == '37') {
     $positions = 'นักจัดการทั่วไป';
 } elseif ($positions == '38') {
     $positions = 'นักวิชการเงินและบัญชี';
 } elseif ($positions_id == '39') {
     $positions = 'เจ้าพนักงานเวชกรฉุกเฉิน';
 } elseif ($positions == '41') {
     $positions = 'นักการแพทย์แผนไทย';
 } elseif ($positions_id == '43') {
     $positions = 'เจ้าหน้าที่ตรวจจอประสาทตา';
 } elseif ($positions == '44') {
     $positions = 'นักพัสดุ';
 } elseif ($positions_id == '45') {
     $positions = 'นักวิชาการคอมพิวเตอร์';
 } elseif ($positions_id == '46') {
     $positions = 'เจ้าหน้าทีบันทึกข้อมูล';
 } elseif ($positions_id == '47') {
     $positions = 'ผู้ช่วยเหลือแพทย์แผนไทย';
 } elseif ($positions_id == '48') {
     $positions = 'เจ้าพนักงานแพทย์แผนไทย';
 } elseif ($positions_id == '49') {
     $positions = 'พนักงานธุรการ';
 } elseif ($positions_id == '50') {
     $positions = 'นักวิชาการโสตทัศนศึกษา';
 } elseif ($positions_id == '51') {
     $positions = 'นักจิตวิทยา';
 } elseif ($positions_id == '52') {
     $positions = 'นักโภชนาการ';
 } elseif ($positions_id == '53') {
     $positions = 'ช่างไฟฟ้าและอิเล็กทรอนิกส์';
 } elseif ($positions_id == '54') {
     $positions = 'พนักงานบริการ';
 } elseif ($positions_id == '55') {
     $positions = 'พนักงานประกอบอาหาร';
 } elseif ($positions_id == '57') {
     $positions = 'พนักงานเกษตรพื้นฐาน';
 } else {
     $positions = ' ไม่ระบุ ';
 }
// $departments = $model->person->dep_id;
// if ($department_id == '32') {
//     $department = 'xxx';
// } elseif ($department_id == '1') {
//     $department = ' B';
// } else {
//     $department = ' ไม่ระบุ ';
// }
$driver = $model->driver->driver_name;
$vehicle = $model->vehicle->license;
$destination = $model->destination;
$passenger = $model->passenger;
$description = $model->description;
$datestart = $model->date_start;
$dateend = $model->date_end;
$license = $model->vehicle->license;
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
	
<div style="position: absolute; z-index: -1; top: 31%; left: 10%; transform: translate(10%, -80%) rotate(-45deg); opacity: 0.2; font-size: 1em; color: pink; font-style: italic;">
    โรงพยาบาลม่วงสามสิบ
</div>
<div style="position: absolute; z-index: -1; top: 42%; left: 25%; transform: translate(-50%, -50%) rotate(-45deg); opacity: 0.2; font-size: 1em; color: pink; font-style: italic;">
    โรงพยาบาลม่วงสามสิบ
</div>
<div style="position: absolute; z-index: -1; top: 65%; left: 65%; transform: translate(-50%, -50%) rotate(-45deg); opacity: 0.2; font-size: 1em; color: pink; font-style: italic;">
    โรงพยาบาลม่วงสามสิบ
</div>
<div style="position: absolute; z-index: -1; top: 90%; left: 0%; transform: translate(-30%, -60%) rotate(-90deg); opacity: 0.2; font-size: 1em; color: pink; font-style: italic;">
    โรงพยาบาลม่วงสามสิบ
</div>


        <div style="text-align:center;">
        <h4 align="right"><strong>แบบ ๓</strong></h4>
            <h2><u><b>ใบขออนุญาตใช้รถส่วนกลาง</b></u></h2>
        </div>
        <div style="text-align:right;">
            <!--<br>วันที่<?= Html::encode(' ' . $d) ?> เดือน<?= Html::encode(' ' . $trans_m) ?> พ.ศ. <?= Html::encode(' ' . $y) ?>-->
			<?php
$thai_months = [
    "01" => "มกราคม", "02" => "กุมภาพันธ์", "03" => "มีนาคม", "04" => "เมษายน",
    "05" => "พฤษภาคม", "06" => "มิถุนายน", "07" => "กรกฎาคม", "08" => "สิงหาคม",
    "09" => "กันยายน", "10" => "ตุลาคม", "11" => "พฤศจิกายน", "12" => "ธันวาคม"
];

$date_parts = explode('-', $datestart);
$d_new = (int) $date_parts[2]; // วันที่
$m_new = $thai_months[$date_parts[1]]; // เดือน
$y_new = $date_parts[0] + 543; // ปี พ.ศ.

$formatted_date = "วันที่ $d_new เดือน $m_new พ.ศ. $y_new";
?>

<br><?= Html::encode($formatted_date) ?>
        </div>
        <div style="text-align:justify;">
            <br><br><strong>เรียน</strong> ผู้อำนวยการโรงพยาบาลม่วงสามสิบ  

           <br><br>
<strong>ข้าพเจ้า</strong>(นาย/นาง/นางสาว)
<span style="display: inline-block; border-bottom: 1px dotted black; padding-bottom: 2px;">
    <?= Html::encode(' ' . $firstname . ' ' . $lastname . ' ') ?>
</span>

           &emsp;&emsp;&emsp;&emsp;&emsp;<strong>ตำแหน่ง</strong>
<span style="display: inline-block; border-bottom: 1px dotted black; padding-bottom: 2px;">
    <?= Html::encode(' ' . $positions . ' ') ?>
</span>
<br><br><strong>ขออนุญาตใช้รถ(ไปที่ไหน)</strong> 
<span style="display: inline-block; border-bottom: 1px dotted black; padding-bottom: 2px;">
    <?= Html::encode(' ' . $destination) ?>
</span>
<br><br><strong>เพื่อไปปฏิบัติราชการเรื่อง</strong>
<span style="display: inline-block; border-bottom: 1px dotted black; padding-bottom: 2px;">
    <?= Html::encode(' ' . $description) ?>
</span>
            <!-- <br>หน่วยงาน<?= Html::encode(' ' . $department) ?> -->
<br><br><strong>มีคนนั่งจำนวน</strong>
<span style="display: inline-block; border-bottom: 1px dotted black; padding-bottom: 2px;">
     <?= Html::encode(' ' . $passenger) ?> คน
</span>   
 <br><br><strong>ในวันและเวลาที่</strong>
<span style="display: inline-block; border-bottom: 1px dotted black; padding-bottom: 2px;">
     <?= Html::encode(' ' . $datestart) ?>
</span>  
&emsp; <strong>ถึงวันและเวลาที่</strong>
<span style="display: inline-block; border-bottom: 1px dotted black; padding-bottom: 2px;">
     <?= Html::encode(' ' . $dateend) ?>
</span>  
<br><br><strong>พนักงานขับรถ::</strong>
<span style="display: inline-block; border-bottom: 1px dotted black; padding-bottom: 2px;">
      <?= Html::encode(' ' . $driver) ?>
</span>                  
              
   
             <!-- &emsp;&emsp;&emsp;&emsp;&emsp;พนักงานขับรถ:: <?= Html::encode(' ' . $driver) ?>   -->
             &emsp;&emsp;&emsp; <strong>ทะเบียนรถ::</strong> <?= Html::encode(' ' . $license) ?> 
<br><br><strong>ผู้จัดรถ::</strong>
<span style="display: inline-block; border-bottom: 1px dotted black; padding-bottom: 2px;">
      <?= Html::encode(' ' . $firstname1 . ' ' . $lastname1 . ' ') ?>
</span>    			 
              
        </div>
        <small>
            <br><br>
            &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
          <span style="border-bottom: 1px dotted black; padding-bottom: 1px;">
		    ..........................................................................
			</span>
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
            <h2><u><b>การรับ-ส่งรถก่อนและหลังใช้งาน</u></b></h2>
        </div>     
        <br>1.การรับรถ&emsp;&emsp; <img src="images/4953048-200.png/"> ปกติ &emsp;&emsp; <img src="images/4953048-200.png/"> ผิดปกติ...............................................................................................................
        <br>&emsp;ก่อนใช้งาน   &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;  ผู้ส่ง...........................................................   ผู้รับ...........................................................
        <br>2.การส่งรถ&emsp;&emsp; <img src="images/4953048-200.png/"> ปกติ &emsp;&emsp; <img src="images/4953048-200.png/"> ผิดปกติ...............................................................................................................
        <br>&emsp;หลังใช้งาน   &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;  ผู้ส่ง...........................................................   ผู้รับ...........................................................
        <br>หมายเหตุ::ก่อนรับรถทุกครั้งให้ตรวจสอบสภาพรถและอุปกรณ์ประจำรถโดยละเอียด
        </small>
    </body> 
</html>
