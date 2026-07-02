<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use yii\helpers\Url;
use yii\data\ActiveDataProvider;


?>
<style>
    /* นำเข้าฟอนต์ที่สวยงามจาก Google Fonts */
    @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&family=Noto+Sans+Thai:wght@400;500&display=swap');
    
    /* ปรับฟอนต์ให้เป็น Roboto หรือ Noto Sans Thai */
    body, h2, th, td {
        font-family: 'Roboto', 'Noto Sans Thai', sans-serif;
        font-weight: 400; /* ฟอนต์บาง */
        font-size: 14px;   /* ขนาดฟอนต์ในตาราง */
        color: #333333;   /* สีฟอนต์เข้มเพื่อให้คมชัด */
    }

    /* ปรับขนาดฟอนต์ของหัวตาราง */
    .kv-grid-table th {
        font-weight: 600;  /* ฟอนต์ตัวหนาสำหรับหัวตาราง */
        font-size: 16px;   /* ขนาดฟอนต์หัวตาราง */
        background-color: #2196F3; /* พื้นหลังฟ้า */
        color: white;      /* ตัวอักษรสีขาว */
    }

    /* เพิ่มขอบตาราง */
    .table-bordered {
        border: 1px solid #ddd;  /* ขอบตารางสีอ่อน */
    }

    /* เพิ่มการเว้นระยะในเซลล์ */
    .table td, .table th {
        padding: 10px;  /* เพิ่มระยะห่างในแต่ละเซลล์ */
    }

    /* ปรับการแสดงปุ่มในตารางให้ดูสวยงาม */
    .btn-primary {
        font-size: 12px;
        background-color: #d671f5;
        border-color: #d671f5;
    }
    .btn-primary:hover {
        background-color: #ad55d6;
        border-color: #ad55d6;
    }

    /* การทำให้ปุ่มในแต่ละคอลัมน์ดูเด่น */
    .kv-grid-container button {
        font-size: 12px;
        padding: 5px 10px;
        border-radius: 4px;  /* ทำให้ปุ่มมีมุมโค้ง */
    }

    /* เพิ่มการทำให้ตัวเลขในคอลัมน์ดูคมชัด */
    .text-center, .text-left {
        font-weight: 500;   /* ฟอนต์ตัวหนาสำหรับข้อความในเซลล์ */
    }

    /* ให้ฟอนต์ในข้อความที่เป็นหัวข้อดูเด่น */
    .card-title {
        font-size: 24px; /* ขนาดฟอนต์หัวข้อ */
        font-weight: 700; /* ตัวหนามากขึ้น */
        color: #007bff;   /* สีฟ้า */
    }
</style>

 <div class="col-md-12 mb-6">
            <div class="card-body">
                <div class="well">
                   <h2 class="card-title text-primary mb-3">
    ทับหม้อเกลือ-เยี่ยมหลังคลอด
    
</h2>
 <?= GridView::widget([
                        'dataProvider' => $dataProvider,
						'layout' => "{items}", // เอา layout อื่นออกเพื่อไม่ให้แสดง summary
                        'tableOptions' => ['class' => 'table table-bordered table-hover table-striped'],
                        'panel' => [
            'before'=>'รายงานทับหม้อเกลือ -เยี่ยมหลังคลอด '
            ],
    'columns' => [
    ['class' => 'kartik\grid\SerialColumn'],

    [
        'attribute' => 'provider',
        'label' => 'provider',
        'contentOptions' => ['class' => 'text-left', 'style' => 'color: green;'],
        'headerOptions' => ['style' => 'background-color: #2196F3; color: white;'], // พื้นหลังฟ้า อักษรขาว
    ],

    [
        'attribute' => 'HN',
        //'label' => 'provider',
        'contentOptions' => ['class' => 'text-left'],
        'headerOptions' => ['style' => 'background-color: #2196F3; color: white;'],
    ],
    [
        'attribute' => 'ข้อมูลมารดาหลังคลอด',
        //'label' => 'provider',
        'contentOptions' => ['class' => 'text-left'],
        'headerOptions' => ['style' => 'background-color: #2196F3; color: white;'],
    ],
   
    [
        'attribute' => 'age',
        'label' => 'อายุ มารดา',
        'contentOptions' => ['class' => 'text-center'],
        'headerOptions' => ['style' => 'background-color: #2196F3; color: white;'],
    ],
	[
        'attribute' => 'เบอร์โทรศัพท์มารดา',
        //'label' => 'provider',
        'contentOptions' => ['class' => 'text-left'],
        'headerOptions' => ['style' => 'background-color: #2196F3; color: white;'],
    ],
	[
        'attribute' => 'HN บุตร',
        //'label' => 'provider',
        'contentOptions' => ['class' => 'text-left'],
        'headerOptions' => ['style' => 'background-color: #2196F3; color: white;'],
    ],
    [
        'attribute' => 'ชื่อ-สกุล บุตร',
        //'label' => 'provider',
        'contentOptions' => ['class' => 'text-left'],
        'headerOptions' => ['style' => 'background-color: #2196F3; color: white;'],
    ],
	
    [
        'attribute' => 'birthdate',
        'format' => ['date', 'php:d/m/Y'],
        'label' => 'วันเกิดบุตร',
        'contentOptions' => ['class' => 'text-center'],
        'headerOptions' => ['style' => 'background-color: #2196F3; color: white;'],
    ],
	[
    'attribute' => 'จำนวนครั้ง',
     //'label' => 'อายุ (วัน)',
    'headerOptions' => ['style' => 'background-color: #2196F3; color: white;'],
    'format' => 'raw', // จำเป็นสำหรับแสดง HTML
    'value' => function ($model) {
        return '<button class="btn btn-primary" style="background-color:#d671f5; color:white; font-size:12px;">' 
                . $model['จำนวนครั้ง'] . 
            '</button>';
    },
    'contentOptions' => ['class' => 'text-center'],
	],
    [
    'attribute' => 'months',
    'label' => 'อายุ (เดือน)',
    'headerOptions' => ['style' => 'background-color: #2196F3; color: white;'],
    'format' => 'raw', // จำเป็นสำหรับแสดง HTML
    'value' => function ($model) {
        return '<button class="btn btn-primary" style="background-color:#36a6a8; color:white; font-size:12px;">' 
                . $model['months'] . 
            '</button>';
    },
    'contentOptions' => ['class' => 'text-center'],
	],
	
	[
    'attribute' => 'days',
     'label' => 'อายุ (วัน)',
    'headerOptions' => ['style' => 'background-color: #2196F3; color: white;'],
    'format' => 'raw', // จำเป็นสำหรับแสดง HTML
    'value' => function ($model) {
        return '<button class="btn btn-primary" style="background-color:#1fa8ed; color:white; font-size:12px;">' 
                . $model['days'] . 
            '</button>';
    },
    'contentOptions' => ['class' => 'text-center'],
	],
    
	[
        'attribute' => 'บ้านเลขที่',
        //'label' => 'provider',
        'contentOptions' => ['class' => 'text-left'],
        'headerOptions' => ['style' => 'background-color: #2196F3; color: white;'],
    ],
    [
        'attribute' => 'บ้าน-หมู่ที่',
        //'label' => 'provider',
        'contentOptions' => ['class' => 'text-left'],
        'headerOptions' => ['style' => 'background-color: #2196F3; color: white;'],
    ],
    [
        'attribute' => 'ตำบล',
        //'label' => 'provider',
        'contentOptions' => ['class' => 'text-left'],
        'headerOptions' => ['style' => 'background-color: #2196F3; color: white;'],
    ],
   [
        'attribute' => 'อำเภอ',
        //'label' => 'provider',
        'contentOptions' => ['class' => 'text-left'],
        'headerOptions' => ['style' => 'background-color: #2196F3; color: white;'],
    ],
    [
        'attribute' => 'จังหวัด',
        //'label' => 'provider',
        'contentOptions' => ['class' => 'text-left'],
        'headerOptions' => ['style' => 'background-color: #2196F3; color: white;'],
    ],
    
],

]);
?>
   <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 20px; flex-wrap: wrap;">
    <!-- กลับหน้าหลัก -->
    <div>
        <?= Html::a('⏪ กลับหน้าหลัก', ['thaimed/index'], [
            'class' => 'btn btn-custom',
            'style' => 'font-size: 1.2rem; background-color: skyblue; color: white;'
        ]) ?>
    </div>
</div>