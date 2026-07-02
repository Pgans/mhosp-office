<?php
use yii\helpers\Html;

?>
<h1>หมวดรายงานอุปกรณ์คอมพิวเตอร์</h1>
<div class="row">
<div class = "col-sm-4"> <a href ="" class="btn btn-danger">อุปกรณ์คอมพิวเตอร์รวมทุกปีงบประมาณ</a></div>
</div>
<p>
    <?=  Html::a('1.รายงานจำนวนอุปกรณ์ในระบบ',['computer/countdevices']) ?> 
</p>
<p>
    <?=  Html::a('2.รายงานที่จำหน่ายอุปกรณ์ที่ชำรุด',['computer/saledevices']) ?>
</p>
<p>
    <?=  Html::a('3.รายงานการส่งซ่อมภายนอก',['computer/serviceout']) ?>
</p>
<div class="row">
<div class = "col-sm-4"> <a href ="" class="btn btn-warning">การซื้ออุปกรณ์ใหม่ ตามช่วงเวลาที่กำหนด(แบบรวมทุกประเภท)</a></div>
</div>
<p>
    <?=  Html::a('1.รายงานการอุปกรณ์ใหม่ทั้งตามแผนและทดแทน',['computer/devicenew']) ?> 
</p>
<p>
    <?=  Html::a('2.xxxxxxxxxxxxxxxxx',['computer/countdevices']) ?> 
</p>
<p>
    <?=  Html::a('3.xxxxxxxxxxxxxxxxx',['computer/countdevices']) ?> 
</p>

<div class="row">
<div class = "col-sm-4"> <a href ="" class="btn btn-success">การซื้ออุปกรณ์ใหม่ ตามช่วงเวลาที่กำหนด(แบบแยกนับตามประเภท)</a></div>
</div>
<p>
    <?=  Html::a('1.รายงานการซื้ออุปกรณ์ปีงบประมาณ',['computer/device59']) ?> 
</p>
<p>
    <?=  Html::a('2.xxxxxxxxxxxxxxxxx',['computer/countdevices']) ?> 
</p>
<p>
    <?=  Html::a('3.xxxxxxxxxxxxxxxxx',['computer/countdevices']) ?> 
</p>


