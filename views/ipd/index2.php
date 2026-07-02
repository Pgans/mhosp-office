<?php
use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\widgets\ActiveForm;
use yii\jui\DatePicker;
use miloschuman\highcharts\Highcharts;
use yii\helpers\Json;
use yii\bootstrap\Modal;
//use kartik\export\ExportMenu;

$this->title = 'รายงานบริการผู้ป่วยใน';
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
        'value' => Yii::$app->request->get('start_date', date('Y-m-d', strtotime('-5 days'))),
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
    <?= Html::label('เลือกแผนก', 'ward_no', ['class' => 'form-label font-weight-bold']) ?>
    <?php
    // กำหนดตัวเลือก ward_no
    $wards = [
        'ALL' => 'ทั้งหมด',
        '38' => 'Ward 2',
        '39' => 'Ward 1',
        '22' => 'LR',
		'50' => 'HomeWard',
		'55' => 'Ward4',
    ];
    ?>

    <?= Html::dropDownList(
        'ward_no',
        Yii::$app->request->get('ward_no', 'ALL'), // ค่าเริ่มต้นคือ 'ALL'
        $wards, // รายการตัวเลือกที่กำหนดเอง
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
		.card {
    display: flex;
    flex-direction: column;
    height: 100%; /* ให้การ์ดขยายเต็มพื้นที่ */
}
.flex-fill {
    flex: 1 1 auto; /* ปรับขนาดความสูงเท่าๆ กัน */
}


    </style>
	
</head>
<body>
     
<div class="container-fluid">
    <div class="row mb-3">
        <!-- 10 อันดับโรค -->
        <div class="col-md-5 mb-4 d-flex align-items-stretch">
            <div class="card flex-fill">
                <div class="card-body">
                    <div class="well">
                        <?= GridView::widget([
                            'dataProvider' => $groupProvider,
                            'layout' => "{items}", // เอา layout อื่นออกเพื่อไม่ให้แสดง summary
							
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
            </div>
        </div>

        <!-- กราฟแสดงข้อมูล -->
        <div class="col-md-4 mb-4 d-flex align-items-stretch">
    <div class="card flex-fill">
        <div class="card-body">
            <div class="well">
                <div class="report-table">
                    <?= \yii\grid\GridView::widget([
                        'dataProvider' => $insclProvider,
						'layout' => "{items}", // เอา layout อื่นออกเพื่อไม่ให้แสดง summary
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            [
                                'attribute' => 'inscl',
                                'label' => 'Insurance Name',
                            ],
                            [
                                'attribute' => 'Visit',
                                'label' => 'ครั้ง',
                            ],
                            [
                                'attribute' => 'amount',
                                'label' => 'คน',
                            ],
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>



        <!-- ตารางผลลัพธ์ -->
        <div class="col-md-12 mb-6">
            <div class="card-body">
                <div class="well">
                   <h5 class="card-title text-primary mb-3">
    ผลลัพธ์รายงานบริการผู้ป่วยนอก 
    <?php if (!empty($departmentName)): ?>
        - แผนก: <?= htmlspecialchars($departmentName, ENT_QUOTES, 'UTF-8') ?>
    <?php endif; ?>วันที่เริ่มต้น: <?= $startDate ?>----วันที่สิ้นสุด: <?= $endDate ?>
</h5>


                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
						'layout' => "{items}", // เอา layout อื่นออกเพื่อไม่ให้แสดง summary
                        'tableOptions' => ['class' => 'table table-bordered table-hover table-striped'],
                        'showPageSummary' => true, // เปิดการแสดงผลรวม
						
                        'columns' => [
                            ['class' => 'kartik\grid\SerialColumn'],
                            [
                                'attribute' => 'ชื่อเดือน',
                                'label' => 'เดือน',
                            ],
                            [
                                'attribute' => 'ปี',
                                'label' => 'ปี พ.ศ.',
                                'pageSummary' => 'รวมทั้งหมด',
                            ],
							[
                                'attribute' => 'visit',
                                'label' => 'จำนวนครั้ง',
                                'contentOptions' => ['class' => 'text-center'],  // จัดตำแหน่งตัวเลขให้ตรงกลาง
                                'pageSummary' => true,
                            ],
                            [
                                'attribute' => 'kon',
                                'label' => 'จำนวนคน',
                                'contentOptions' => ['class' => 'text-center'],  // จัดตำแหน่งตัวเลขให้ตรงกลาง
                                'pageSummary' => true,
                            ],
                            [
                                'attribute' => 'refers',
                                'label' => 'ส่งต่อ',
                                'contentOptions' => ['class' => 'text-center'],  // จัดตำแหน่งตัวเลขให้ตรงกลาง
                                'pageSummary' => true,
                            ],
                            
                            
							[
                                'attribute' => 'ward1',
                                'label' => 'ผู้ป่วยใน1',
                                'contentOptions' => ['class' => 'text-center'],  // จัดตำแหน่งตัวเลขให้ตรงกลาง
                                'pageSummary' => true,
                            ],
							[
                                'attribute' => 'ward2',
                                'label' => 'ผู้ป่วยใน2',
                                'contentOptions' => ['class' => 'text-center'],  // จัดตำแหน่งตัวเลขให้ตรงกลาง
                                'pageSummary' => true,
                            ],
                            [
                                'attribute' => 'lr',
                                'label' => 'ห้องคลอด',
                                'contentOptions' => ['class' => 'text-center'],  // จัดตำแหน่งตัวเลขให้ตรงกลาง
                                'pageSummary' => true,
                            ],
							[
                                'attribute' => 'HomeWard',
                                'label' => 'HomeWard',
                                'contentOptions' => ['class' => 'text-center'],  // จัดตำแหน่งตัวเลขให้ตรงกลาง
                                'pageSummary' => true,
                            ],
							[
                                'attribute' => 'ward4',
                                'label' => 'ผู้ป่วยใน4',
                                'contentOptions' => ['class' => 'text-center'],  // จัดตำแหน่งตัวเลขให้ตรงกลาง
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
    
	
    <!-- ข้อมูลแยกตามรายคน -->
    <div class="row">
        <div class="col-xl-12 col-md-12">
            <div class="well">
                <div class="card mt-3 border-success shadow-lg">
                    <div class="card-header bg-success text-white">
                        <h4 class="m-0">ข้อมูลแยกตามรายคน <?php if (!empty($departmentName)): ?>
        - แผนก: <?= htmlspecialchars($departmentName, ENT_QUOTES, 'UTF-8') ?>
    <?php endif; ?></h4>
                    </div>
					
                    
                        <?= GridView::widget([
                            'dataProvider' => $visitProvider,
                            'tableOptions' => ['class' => 'table table-bordered table-striped table-hover'],
							
							
        'panel' => [
            'before'=>'<b style="color:blue "></b>',
            'after'=>'ประมวลผล '.date('Y-m-d H:i:s')
            ],
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
								/*
                                [
                                    'attribute' => 'admitdate',
                                    'label' => 'วันที่เข้ารักษา',
                                    'format' => ['date', 'php:d/m/Y'],
                                    'contentOptions' => ['class' => 'text-center'],
                                ],
								/*
                                [
                                    'attribute' => 'referdate',
                                    'label' => 'วันเวลาส่งต่อ',
                                    'format' => ['date', 'php:d/m/Y'],
                                    'contentOptions' => ['class' => 'text-center'],
                                ],
								*/
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
								/*
                                [
                                    'attribute' => 'times',
                                    'label' => 'ส่งต่อ/ชั่วโมง',
                                    'format' => ['decimal', 2],
                                    'contentOptions' => ['class' => 'text-right text-danger'],
                                ],
								*/
                            ],
                            'summary' => '<div style="font-size: 1.2rem; font-weight: bold;">แสดง {begin} - {end} จาก {totalCount} รายการ</div>',
    
]);
?>
                      
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
