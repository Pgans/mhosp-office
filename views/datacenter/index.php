<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use \miloschuman\highcharts\Highcharts;
use yii\web\JsExpression;
use kartik\grid\GridView;
use kartik\export\ExportMenu;

/* @var $this yii\web\View */

$this->title = 'M30hospital(045489064)';
?>

<div class='well'>
<a href="http://192.168.200.9/yii2a-services/frontend/web/index.php?r=jobcom%2Findex" target="_blank"><img src="images/opd-10.png"
         title="แจ้งซ่อมคอมพิวเตอร์และโสตทัศนศึกษา" width="140" height="140"></a>
<a href="http://192.168.200.9/yii2a-services/frontend/web/index.php?r=jobservice%2Findex" target="_blank"><img src="images/ipd-10.png" 
         title="แจ้งหน่วยซ่อมบำรุง มีระบบแจ้งเตือนเข้าไลน์กลุ่มผู้ดูแลหน่วยซ่อมบำรุง" width="140" height="140"></a>
<!--
<a href="http://192.168.200.9/yii2a-services/frontend/web/index.php?r=carjobs%2Findex" target="_blank"><img src="images/car2.jpg"
         title="โปรแกรมบันทึกการใช้รถ" width="130" height="130"></a>

<a href="http://192.168.200.9/yii2a-services/frontend/web/index.php?r=orderoils%2Findex" target="_blank"><img src="images/oils.jpg"
         title="โปรแกรมเบิกน้ำมันเชื้อเพลิงควบคุมโรคSRRT" width="130" height="130"></a>

<a href="http://192.168.200.9/yii2a-services/frontend/web/index.php?r=opdcard%2Fpermits" target="_blank"><img src="images/register.jpg" 
         title="โปรแกรมยืมเวชระเบียน ระบบมีการLogin ระเบียบการคืนภายใน7 วัน" width="130" height="130"></a>
         <a href="http://192.168.200.9/mhosp-office/web/index.php?r=user%2Fsecurity%2Flogin" target="_blank"><img src="images/bk002.png" 
         title="โปรแกรมยืมเวชระเบียน ระบบมีการLogin ระเบียบการคืนภายใน7 วัน" width="130" height="130"></a>
		  <a href="http://192.168.200.9/mhosp-office/web/index.php?r=jobmedical%2Findex" target="_blank"><img src="images/background2xx.png" 
         title="โปรแกรมส่งซ่อมเครื่องมือแพทย์" width="130" height="130"></a>
<br>


 <!-- <button type="button" class="btn btn-primary btn-lg" onclick="location.href='http://localhost/yii2a-services/frontend/web/index.php?r=jobcom%2Findex'">แจ้งซ่อมคอมพิวเตอร์และโสตทัศนศึกษา</button>
 <button type="button" class="btn btn-info btn-lg" onclick="location.href='http://localhost/yii2a-services/frontend/web/index.php?r=jobservice%2Findex'">แจ้งซ่อมหน่วยซ่อมบำรุง</button> 
 <button type="button" class="btn btn-warning btn-lg" onclick="location.href='http://localhost/yii2a-services/frontend/web/index.php?r=carjobs%2Findex'">บันทึกการใช้ยานพาหนะ</button>
 <button type="button" class="btn btn-success btn-lg" onclick="location.href='http://localhost/yii2a-services/frontend/web/index.php?r=opdcard%2Fpermits'">ยืมเวชระเบียน</button> 
 <button type="button" class="btn btn-info btn-lg" onclick="location.href='http://localhost/yii2a-services/frontend/web/index.php?r=apdcard%2Fpermits'">คืนเวชระเบียน(Admin)</button>     
  -->
  
</div>
