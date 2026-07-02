<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Event */

$this->title = $model->title; // กำหนดชื่อหัวเรื่อง
$this->params['breadcrumbs'][] = ['label' => 'Event Calendar', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="-view"><div class="box box-primary box-solid">
        <div class="box-header">
            <h3 class="box-title"><i class="fa fa-file-text-o"></i> <?= Html::encode($this->title) ?></h3>
        </div>
        <div class="box-body">
    <p>
	  <p><a class='btn btn-info' HREF="javascript:history.back()" ><i class="fa fa-reply"></i> ย้อนกลับ</a>  
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?> <!-- ปุ่มแก้ไข -->
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?', // ข้อความยืนยันการลบ
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <!-- ใช้ DetailView เพื่อแสดงรายละเอียดของกิจกรรม -->
    <?= DetailView::widget([
        'model' => $model, // โมเดลที่ต้องการแสดง
        'attributes' => [ // คุณสมบัติที่จะถูกแสดง
            'id',
            'title', // ชื่อกิจกรรม
            'start', // เวลาเริ่มต้น
            'end', // เวลาสิ้นสุด
            'description', // คำอธิบาย
        ],
    ]) ?>

</div>
