<?php
use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\widgets\ActiveForm;
use yii\jui\DatePicker;
use miloschuman\highcharts\Highcharts;
use yii\helpers\Json;

$this->title = 'รายงานบริการส่งต่อผู้ป่วยนอก';
?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
    /* สไตล์สำหรับคลาส well */
    .well {
        background-color: #f8f9fa; /* สีเทาอ่อน */
        border: 1px solid #d6d8db; /* ขอบสีเทา */
        border-radius: 12px; /* ขอบมน */
        box-shadow: inset 2px 2px 5px rgba(0, 0, 0, 0.1), 
                    inset -2px -2px 5px rgba(255, 255, 255, 0.7); /* เอฟเฟกต์ 3 มิติ */
        padding: 20px; /* ระยะห่างภายใน */
        margin: 10px 0; /* ระยะห่างภายนอก */
    }

    .well:hover {
        box-shadow: inset 2px 2px 8px rgba(0, 0, 0, 0.2), 
                    inset -2px -2px 8px rgba(255, 255, 255, 0.8); /* เพิ่มความลึกเมื่อ hover */
        background-color: #f1f3f4; /* สีเทาอ่อนขึ้นเมื่อ hover */
    }

    /* สไตล์สำหรับฟอร์ม */
    .form-control {
        border-radius: 10px;
        background-color: #f9f9f9; /* พื้นหลังเทาอ่อน */
        border: 1px solid #d1d1d1; /* ขอบสีเทา */
    }

    .form-control:focus {
        border-color: #349582; /* ขอบสีเขียวเมื่อ focus */
        background-color: #ffffff; /* พื้นหลังขาวเมื่อ focus */
    }

    /* สไตล์สำหรับปุ่ม */
    .btn-success {
        background-color: #4CAF50;
        border: none;
        color: white;
        padding: 10px 20px;
        font-size: 16px;
        border-radius: 8px;
        width: 100%;
    }

    .btn-success:hover {
        background-color: #45a049;
    }

    /* สไตล์สำหรับ GridView */
    .grid-view table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    .grid-view th {
        background-color: #369c76;
        color: #fff;
        padding: 12px;
        text-align: center;
        font-weight: bold;
        border: 1px solid #dee2e6;
    }

    .grid-view td {
        padding: 10px;
        text-align: center;
        border: 1px solid #dee2e6;
    }

    .grid-view tr:nth-child(odd) {
        background-color: #f9f9f9;
    }

    .grid-view tr:nth-child(even) {
        background-color: #eaf0f2;
    }

    .grid-view tr:hover {
        background-color: #d1e7dd;
    }
</style>
<div class="row">
<!-- Section for Date Filters -->
<div class="col-xl-3 col-md-2">
    <div class="card border-primary shadow-lg h-100 py-2">
        <div class="card-body" style="background: linear-gradient(45deg, #d4f4e8, #b0e2d6);">
            <div class="well">
                <h5 class="card-title text-primary mb-3">ช่วงวันที่</h5>
                <?php $form = ActiveForm::begin([
                    'method' => 'get',
                    'action' => ['index'],
                ]); ?>
				<div class="form-group">
    <?= Html::label('วันที่เริ่มต้น', 'start_date', ['class' => 'form-label font-weight-bold']) ?>
    <?= yii\jui\DatePicker::widget([
        'name' => 'start_date',
        'value' => Yii::$app->request->get('start_date', date('Y-m-d', strtotime('-1 month'))),
        'language' => 'th',
        'dateFormat' => 'yyyy-MM-dd',
        'options' => [
            'class' => 'form-control',
        ],
        'clientOptions' => [
            'changeMonth' => true,
            'changeYear' => true,
        ],
    ]) ?>
</div>

<div class="form-group">
    <?= Html::label('วันที่สิ้นสุด', 'end_date', ['class' => 'form-label font-weight-bold']) ?>
    <?= yii\jui\DatePicker::widget([
        'name' => 'end_date',
        'value' => Yii::$app->request->get('end_date', date('Y-m-d')),
        'language' => 'th',
        'dateFormat' => 'yyyy-MM-dd',
        'options' => [
            'class' => 'form-control',
        ],
        'clientOptions' => [
            'changeMonth' => true,
            'changeYear' => true,
        ],
    ]) ?>
</div>



    <div class="form-group">
    <?= Html::label('เลือกแผนก', 'unit_reg', ['class' => 'form-label font-weight-bold']) ?>
    <?php
    // ดึงข้อมูลแผนกจากฐานข้อมูล
    $departments = \yii\helpers\ArrayHelper::map(
        \app\models\ServiceUnits::find()->select(['unit_id', 'unit_name'])->asArray()->all(),
        'unit_id',
        'unit_name'
    );

    // เพิ่มตัวเลือก "ทั้งหมด" ไว้ในรายการ
    $departments = ['ALL' => 'ทั้งหมด'] + $departments;
    ?>

    <?= Html::dropDownList(
        'unit_reg',
        Yii::$app->request->get('unit_reg', 'ALL'), // ค่าเริ่มต้นคือ 'ALL'
        $departments, // รายการตัวเลือกที่ได้จากฐานข้อมูล
        [
            'class' => 'form-control',
        ]
    ) ?>
</div>

	<div class="form-group">
    <?= Html::label('เลือกรหัสโรค (เริ่มต้น)', 'icd_code1', ['class' => 'form-label font-weight-bold']) ?>
    <?= Html::input('text', 'icd_code1', Yii::$app->request->get('icd_code1', ''), [
        'class' => 'form-control',
        'placeholder' => 'เว้นว่างเพื่อเลือกทั้งหมด'
    ]) ?>
</div>

<div class="form-group">
    <?= Html::label('เลือกรหัสโรค (สิ้นสุด)', 'icd_code2', ['class' => 'form-label font-weight-bold']) ?>
    <?= Html::input('text', 'icd_code2', Yii::$app->request->get('icd_code2', ''), [
        'class' => 'form-control',
        'placeholder' => 'เว้นว่างเพื่อเลือกทั้งหมด)'
    ]) ?>
</div>



                <div class="form-group text-center">
                    <?= Html::submitButton('<i class="fas fa-search"></i> ค้นหา', ['class' => 'btn btn-success']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
<style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 0;
            background-color: #f9f9f9;
        }
        .chart-container {
            width: 80%;
            margin: auto;
        }
    </style>
</head>
<body>
   <div class="container-fluid">
        <div class="row">
		
            <!-- กราฟ -->
			
            <div class="col-md-3 mb-3">
                <div class="result-graph">
                    <?php
                        // เตรียมข้อมูลกราฟ
                        $chartData = [];
                        foreach ($monthProvider->getModels() as $model) {
                            $chartData[] = [
                                'name' => $model['ชื่อเดือน'] . ' ' . $model['ปี'],
                                'data' => [
                                    (int)$model['จำนวนครั้งโดยรถโรงพยาบาล'],
                                    (int)$model['ไปเอง'],
                                    (int)$model['จำนวนทั้งหมด'],
                                ]
                            ];
                        }

                        // กำหนดค่ากราฟ
                        echo Highcharts::widget([
                            'options' => [
                                'chart' => [
                                    'type' => 'bar',  // ใช้กราฟแนวนอน (Bar)
                                    'height' => 400,  // ขนาดความสูงของกราฟ
                                ],
                                'title' => [
                                    'text' => 'จำนวนครั้งการใช้งานตามเดือน',  // ชื่อกราฟ
                                ],
                                'xAxis' => [
                                    'categories' => ['รถโรงพยาบาล', 'ไปเอง', 'ทั้งหมด'],  // หมวดหมู่ที่แสดงบนแกน X
                                ],
                                'yAxis' => [
                                    'title' => [
                                        'text' => 'จำนวนครั้ง',
                                    ],
                                ],
                                'series' => $chartData,  // ข้อมูลที่ใช้ในการสร้างกราฟ
                            ]
                        ]);
                    ?>
                </div>
            </div>
      
         
            <!-- ตารางผลลัพธ์ -->
            <div class="col-md-6 mb-6">
                    <div class="card-body">
                        <div class="well">
                            <h5 class="card-title text-primary mb-3">ผลลัพธ์รายงานบริการส่งต่อผู้ป่วยนอก</h5>
                            <?= GridView::widget([
                                'dataProvider' => $dataProvider,
                                'tableOptions' => ['class' => 'table table-bordered table-hover table-striped'],
                                'showPageSummary' => true, // เปิดการแสดงผลรวม
                                'columns' => [
                                    ['class' => 'kartik\grid\SerialColumn'],
                                    [
                                        'attribute' => 'ชื่อเดือน',
                                        'label' => 'เดือน (ภาษาไทย)',
                                    ],
                                    [
                                        'attribute' => 'ปี',
                                        'label' => 'ปี พ.ศ.',
                                        'pageSummary' => 'รวมทั้งหมด',
                                    ],
                                    [
                                        'attribute' => 'refer_count',
                                        'label' => 'refer',
                                        'contentOptions' => ['class' => 'text-center'],  // จัดตำแหน่งตัวเลขให้ตรงกลาง
                                        'pageSummary' => true,
                                    ],
                                    [
                                        'attribute' => 'visit_count',
                                        'label' => 'จำนวนครั้ง',
                                        'contentOptions' => ['class' => 'text-center'],  // จัดตำแหน่งตัวเลขให้ตรงกลาง
                                        'pageSummary' => true,
                                    ],
                                    [
                                        'attribute' => 'person_count',
                                        'label' => 'จำนวนคน',
                                        'contentOptions' => ['class' => 'text-center'],  // จัดตำแหน่งตัวเลขให้ตรงกลาง
                                        'pageSummary' => true,
                                    ],
                                    [
                                        'attribute' => 'admit_count',
                                        'label' => 'admit',
                                        'contentOptions' => ['class' => 'text-center'],  // จัดตำแหน่งตัวเลขให้ตรงกลาง
                                        'pageSummary' => true,
                                    ],
                                    [
                                        'attribute' => 'less_than_2_hours',
                                        'label' => '< 2 ชั่วโมง',
                                        'contentOptions' => ['class' => 'text-center'],  // จัดตำแหน่งตัวเลขให้ตรงกลาง
                                        'pageSummary' => true,
                                    ],
                                    [
                                        'attribute' => 'more_than_2_hours',
                                        'label' => '> 2 ชั่วโมง',
                                        'contentOptions' => ['class' => 'text-center'],  // จัดตำแหน่งตัวเลขให้ตรงกลาง
                                        'pageSummary' => true,
                                    ],
                                    [
                                        'attribute' => 'จำนวนครั้งโดยรถโรงพยาบาล',
                                        'label' => 'โดยรถ รพ.',
                                        'format' => ['integer'],
                                        'pageSummary' => true,
                                    ],
                                    [
                                        'attribute' => 'ไปเอง',
                                        'label' => 'ไปเอง',
                                        'format' => ['integer'],
                                        'pageSummary' => true,
                                    ],
                                ],
                                'summary' => '<div style="font-size: 1.2rem; font-weight: bold;">แสดง {begin} - {end} จาก {totalCount} รายการ</div>',
                                'pager' => [
                                    'class' => 'yii\widgets\LinkPager',
                                    'options' => ['class' => 'pagination justify-content-center'],
                                ],
                            ]); ?>
                        </div>
                    </div>
                </div>
            </div>
       
   <!-- 10 อันดับโรค -->
            <div class="col-md-4 mb-3">
                <div class="top-diseases">
				 <div class="card-body">
                        <div class="well">
               
                    <?= GridView::widget([
                        'dataProvider' => $groupProvider,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'], // ลำดับที่
                            [
                                'attribute' => 'โรค',
                                'label' => 'รหัสโรค',
                            ],
                            [
                                'attribute' => 'รายละเอียดโรค',
                                'label' => 'รายละเอียดโรค',
                            ],
                            [
                                'attribute' => 'จำนวนครั้ง',
                                'label' => 'จำนวนครั้ง',
                                'contentOptions' => ['class' => 'text-center'], // จัดกึ่งกลาง
                            ],
                        ],
                    ]); ?>
                </div>
           </div>
			
        <!-- ข้อมูลแยกตามรายคน -->
        <div class="row">
            <div class="col-xl-12 col-md-12">
                <div class="well">
                    <div class="card mt-3 border-success shadow-lg">
                        <div class="card-header bg-success text-white">
                            <h4 class="m-0">ข้อมูลแยกตามรายคน</h4>
                        </div>
                        <div class="card-body">
                            <?= GridView::widget([
                                'dataProvider' => $hnProvider,
                                'tableOptions' => ['class' => 'table table-bordered table-striped table-hover'],
                                'columns' => [
                                    ['class' => 'yii\grid\SerialColumn'],
                                    [
                                        'attribute' => 'hn',
                                        'label' => 'HN',
                                        'contentOptions' => ['class' => 'text-center'],
                                    ],
                                    [
                                        'attribute' => 'regdate',
                                        'label' => 'วันที่รับบริการ',
                                        'format' => ['date', 'php:d/m/Y'],
                                        'contentOptions' => ['class' => 'text-center'],
                                    ],
                                    [
                                        'attribute' => 'admitdate',
                                        'label' => 'วันที่เข้ารักษา',
                                        'format' => ['date', 'php:d/m/Y'],
                                        'contentOptions' => ['class' => 'text-center'],
                                    ],
                                    [
                                        'attribute' => 'referdate',
                                        'label' => 'วันเวลาส่งต่อ',
                                        'format' => ['date', 'php:d/m/Y'],
                                        'contentOptions' => ['class' => 'text-center'],
                                    ],
                                    [
                                        'attribute' => 'unit_name',
                                        'label' => 'แผนก',
                                        'contentOptions' => ['class' => 'text-center'],
                                    ],
                                    [
                                        'attribute' => 'fullname',
                                        'label' => 'ชื่อ-สกุล',
                                        'contentOptions' => ['class' => 'text-center'],
                                    ],
                                    [
                                        'attribute' => 'เพศ',
                                        'label' => 'เพศ',
                                        'contentOptions' => ['class' => 'text-center'],
                                    ],
                                    [
                                        'attribute' => 'age',
                                        'label' => 'อายุ',
                                        'contentOptions' => ['class' => 'text-center'],
                                    ],
                                    [
                                        'attribute' => 'an',
                                        'label' => 'admit',
                                    ],
                                    [
                                        'attribute' => 'refer',
                                        'label' => 'ส่งต่อ',
                                    ],
                                    [
                                        'attribute' => 'diag',
                                        'label' => 'รหัสโรค',
                                    ],
                                    [
                                        'attribute' => 'times',
                                        'label' => 'ส่งต่อ/ชั่วโมง',
                                        'format' => ['decimal', 2],
                                        'contentOptions' => ['class' => 'text-right text-danger'],
                                    ],
                                ],
                                'summary' => '<div style="font-size: 1.2rem; font-weight: bold;">แสดง {begin} - {end} จาก {totalCount} รายการ</div>',
                                'pager' => [
                                    'class' => 'yii\widgets\LinkPager',
                                    'options' => ['class' => 'pagination justify-content-center'],
                                ],
                            ]); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
