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
        <div style="text-align:center;">
        <h4 align="right">แบบ3 </h4>
            <h2>แบบขออนุมัติใช้รถส่วนกลาง </h2>
        </div>
        <div style="text-align:right;">
            <br>วันที่<?= Html::encode(' ' . $d) ?> เดือน<?= Html::encode(' ' . $trans_m) ?> พ.ศ. <?= Html::encode(' ' . $y) ?>
        </div>
        <div style="text-align:justify;">
            <br><br>เรียน ผู้อำนวยการโรงพยาบาลม่วงสามสิบ  

            <br><br>ข้าพเจ้า(นาย/นาง/นางสาว)<?= Html::encode(' ' . $firstname . ' ' . $lastname . ' ') ?>
            &emsp;&emsp;&emsp;&emsp;&emsp; ตำแหน่ง<?= Html::encode(' ' . $positions) ?>
            <!-- <br>หน่วยงาน<?= Html::encode(' ' . $department) ?> -->
            <br><br>ขออนุญาตใช้รถ(ไปที่ไหน)  <?= Html::encode(' ' . $destination) ?>
            <br><br>เพื่อไปปฏิบัติราชการเรื่อง  <?= Html::encode(' ' . $description) ?>
            <br><br>มีคนนั่งจำนวน<?= Html::encode(' ' . $passenger) ?> คน
            <br><br>ในวันและเวลาที่<?= Html::encode(' ' . $datestart) ?>
            &emsp; ถึงวันและเวลาที่<?= Html::encode(' ' . $dateend) ?>
             <!-- &emsp;&emsp;&emsp;&emsp;&emsp;พนักงานขับรถ:: <?= Html::encode(' ' . $driver) ?>   -->
             <br><br>พนักงานขับรถ:: <?= Html::encode(' ' . $driver) ?>&emsp;&emsp;&emsp; ทะเบียนรถ:: <?= Html::encode(' ' . $license) ?>  
             <br><br>ผู้จัดรถ<?= Html::encode(' ' . $firstname1 . ' ' . $lastname1 . ' ') ?> 
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
            <h2>บันทึกผู้ขับรถและยามรักษาการ</h2>
        </div>     
        <br>หมายเลขทะเบียนรถ&emsp;&emsp;<?= Html::encode(' ' . $license) ?>
        &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
        วันที่<?= Html::encode(' ' . $d) ?> เดือน<?= Html::encode(' ' . $trans_m) ?> พ.ศ. <?= Html::encode(' ' . $y) ?>
        <br>เวลาออก ............................................. น.
        &emsp;&emsp;&emsp;
        เลขกิโลเมตรเมื่อออก ............................... กม.
        <br>เวลากลับ ............................................. น.
        &emsp;&emsp;&emsp;
        เลขกิโลเมตรเมื่อกลับ ............................... กม.
        <br>
        &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
        รวมเป็นระยะทาง ..................................... กม.
        <small>
            <br><br><br>
            &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
            ....................................................................... พนักงานขับรถ
            <br>
            &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
            &emsp;&emsp;&emsp;&emsp;(<?= Html::encode(' ' . $driver) ?> )
            <br><br><br>
            &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
            ....................................................................... รปภ. ผู้บันทึกข้อมูล
            <br>
            &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
            (.......................................................................)
        </small>
    </body> 
</html>
