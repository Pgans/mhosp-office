<?php
use yii\helpers\Html;
use app\models\orderoils;
use app\models\mooban;
use app\models\District;
?>

<table>
    <tr>
        <td align="center">
            <h3>ระบบบันทึกการเบิกน้ำมันเชื้อเพลิงควบคุมโรคทีมสอบสวนเคลื่อนที่เร็ว(SRRT)</h3>
            <strong><i>โรงพยาบาลม่วงสามสิบ(Muangsamsib Hospital)</i></strong><br />
            <small>เบอร์โทร 045-489064</small>
            <h4> 
         <?php   $_month_name = array("01"=>"มกราคม",  "02"=>"กุมภาพันธ์",  "03"=>"มีนาคม",   

                                        "04"=>"เมษายน",  "05"=>"พฤษภาคม",  "06"=>"มิถุนายน",   

                                        "07"=>"กรกฎาคม",  "08"=>"สิงหาคม",  "09"=>"กันยายน",   

                                        "10"=>"ตุลาคม", "11"=>"พฤศจิกายน",  "12"=>"ธันวาคม");

                            $vardate=date('Y-m-d');

                            $yy=date('Y');

                            $mm =date('m');$dd=date('d');

                            if ($dd<10){

                            $dd=substr($dd,1,2);

                            }

                            $date=$dd ." ".$_month_name[$mm]."  ".$yy+= 543;

                            echo $date; 

                            ?>
            </h4>
        </td>
    </tr>
    <br>
    <br>
    <br>
    
    
</table>
<table class="table_bordered" width="100%" border="0" cellpadding="1" cellspacing="0">
<tr>
<td colspan="3">ชื่อ-นามสกุล: <br /><?= Html::encode($model->fullname) ?></td>
<td colspan="2">วินิจฉัยโรค::<br /> <?= Html::encode($model->diagnosis) ?></td>
  </tr>       
    <tr>
        <td colspan="3">ที่อยู่:<br /><?= Html::encode($model->mooban->mooban_name) ?>-<?= Html::encode($model->district->DISTRICT_NAME) ?>
        -<?= Html::encode($model->amphur->AMPHUR_NAME)?>-<?= Html::encode($model->province->PROVINCE_NAME)?>
    </td>
        <td colspan="2">โรงพยาบาลส่งเสริมสุขภาพตำบล:<br /><?= Html::encode($model->anamai->hospname) ?></td>
    </tr>
    <!-- <tr>
        <td>Specimen ID Number:<br /> fullname</td>
        <td colspan="2">Date of Request:<br /> Request Date</td>f
        <td>Date of Accession:<br /> Register Date</td>
        <td>Date of Report:<br /> Report at</td>
    </tr>
    <tr>
        <td colspan="5">Diagnosis:<br /> Diagnosis</td>
    </tr> -->
</table>

<!-- <table cellspacing="0" cellpadding="2" border="0" width="100%">
    <tr>
        <td width="50%">
            <table cellspacing="0" cellpadding="0" class="table_bordered" width="100%">
                <tr>
                    <td colspan="2">
                        <u>กิจกรรม :</u><br />
                        การใช้น้ำมันควบคุมโรค
                    </td>
                </tr>
                
            </table>
        </td>
        <td width="50%">

            <?=Html::img(Yii::getAlias('@app/web/images/').'spacer.gif', ['width' => 300])?>

        </td>
    </tr>
</table> -->

<table>
 
    <tr>
        <td>
            
        </td>
    </tr>
    <tr>
        <td>
        <h3>กิจกรรม:</h3>
            การใช้น้ำมันควบคุม
        </td>
    </tr>
    
</table>


<table width="100%" >
    <tr>
        <td align="center" colspan="2">กิจกรรม<br /></td>
        <td align="center" colspan="1">จำนวนน้ำมัน<br /></td>
        <td align="center" colspan="4">จำนวนเงิน<br /></td>
        <td align="center" colspan="2">ผู้รับผิดชอบ <br /></td>
    </tr>
    <tr>
        <td align="center" colspan="2"><?= Html::encode($model->spray->spray_name) ?></td>
        <td align="center" colspan="1"><?= Html::encode($model->oilsName) ?></td>
        <td align="center" colspan="4"><br /></td>
        <td align="center" colspan="2"><?= Html::encode($model->creater->firstname) ?></td>
    </tr>
    <tr>
        
    </tr>
    
    
</table>

<table>
 
 <tr>
    
     <td align="right">
     <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    

     <h5 >...........................................................ผู้สั่งจ่าย</h5>
     <br>
         (...........................................................)
    <br>
    <br>
    <h5 >...........................................................ผู้จ่าย</h5>
    <br>
         (...........................................................)
         <br>
    <br>
    <h5 >...........................................................การเงิน</h5>
    <br>
         (...........................................................)
         <br>
    <br>
    <h5 >........................................................หน.งานเวข</h5>
    <br>
         (.........................................................)
     </td>
 </tr>
 <br>
    <br>
    <br>
    <br>
    <br>
    

</table>
