<?php
use yii\helpers\Html;

//$this->params['breadcrumbs'][] = ['label' => 'รายงาน', 'url' => ['report/index']];
//$this->params['breadcrumbs'][] = 'รายงานอุปกรณ์คอมพิวเตอร์';
?>
<br>
<h1>หมวดรายงานศูนย์คอมพิวเตอร์ โรงพยาบาลม่วงสามสิบ</h1>
<div class="row">
<div class = "col-sm-4"> <a href ="" class="btn btn-danger">รายงานเกี่ยวข้องตัวชี้วัด '<b style="color:yellow">......จำนวนเข้าใช้งาน:::</b>  <?= $amount ?></a></div></div>
 
<p>
    <?=  Html::a('1.อัตราการซื้ออุปกรณ์ต่อพวงทางคอมพิวเตอร์',['computer/index']) ?>
</p>
 <p>
   <?= Html::a('2.อัตราการซื้อหมึก (แยกรายเดือน)', ['computer/ink'])?>
</p> 
<p>
   <?= Html::a('3.อัตราการแบตเตอรี่เครื่องสำรองไฟ (แยกรายเดือน)', ['computer/battery'])?>
</p> 
 
