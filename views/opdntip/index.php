<?php
use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\widgets\ActiveForm;
use yii\jui\DatePicker;
use miloschuman\highcharts\Highcharts;
use yii\helpers\Json;
use yii\bootstrap\Modal;
//use kartik\export\ExportMenu;

$this->title = 'รายงานบริการผู้ป่วยนอก NCD Xray แยกราย รพสต.';
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
        background-color: #f8b3d0; /* สีชมพูอ่อน */
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
        background-color: #f9f9f9; /* สีพื้นหลังเทาอ่อนสำหรับแถวคี่ */
    }

    .grid-view tr:nth-child(even) {
        background-color: #eaf0f2; /* สีพื้นหลังเทาอ่อนสำหรับแถวคู่ */
    }

    .grid-view tr:hover {
        background-color: #d1e7dd; /* สีพื้นหลังเมื่อ hover */
    }

    /* การตั้งค่าสำหรับ body */
    body {
        background-color: #f0f0f0; /* สีเทาอ่อน */
    }

    /* กำหนด gradient background */
    .gradient-bg {
        background: linear-gradient(to right, #f8b3d0, #ffebf0); /* Gradient สีชมพูอ่อน */
        padding: 20px; /* ระยะห่างภายใน */
        border-radius: 12px; /* ขอบมน */
    }
</style>


<!--########################  ScollBar ##############################################################-->
<style>
.report-table {
    max-height: 300px; /* กำหนดความสูงสูงสุดที่ต้องการ */
    overflow-y: auto; /* แสดง Scroll Bar เมื่อมีข้อมูลเกิน */
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
         'value' => $startDate ? $startDate : date('Y-m-d'),
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
         'value' => $endDate ? $endDate : date('Y-m-d'), 
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

                <div class="form-group text-center">
                    <?= Html::submitButton('<i class="fas fa-search"></i> ค้นหา', ['class' => 'btn btn-success']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>

      
	
<div class="row">
	<!-- รายงานกหน่วยงาน -->
    <div class="col-md-4 mb-4 d-flex align-items-stretch">
        <div class="card flex-fill">
            <div class="card-body">
                <div class="well">
                    <div class="report-table">
                        <?= GridView::widget([
                            'dataProvider' => $staffProvider,
                            'showPageSummary' => true, // เปิดการแสดงผลรวม
                            'layout' => "{items}",
                            'columns' => [
                                ['class' => 'kartik\grid\SerialColumn'],
                                [
                                    'attribute' => 'hosp_id',
                                    'label' => 'รหัสหน่วยงาน',  
                                ],
                                [
                                    'attribute' => 'hosp_name',
                                    'label' => 'หน่วยงาน',
                                    'pageSummary' => 'รวมทั้งหมด',
                                ],
                                [
                                    'attribute' => 'amount',
                                    'label' => 'จำนวนคน',
                                    'pageSummary' => true, // แสดงผลรวมของจำนวนครั้ง
                                    'format' => ['integer'], // จัดรูปแบบเป็นตัวเลข
                                ],
                            ],
                        ]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- รายงานเดือน -->
    <div class="col-md-4 mb-4 d-flex align-items-stretch">
        <div class="card flex-fill">
            <div class="card-body">
                <div class="well">
				      <h5 class="card-title text-primary mb-3">
     ******รายงานเดือน 3 เดือนย้อนหลัง ******
   
</h5>
                    <div class="report-table">
                        <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            'showPageSummary' => true, // เปิดการแสดงผลรวม
                            'layout' => "{items}",
                            'columns' => [
                                ['class' => 'kartik\grid\SerialColumn'],
                                [
                                'attribute' => 'ชื่อเดือน',
                                'label' => 'เดือน ',
                            ],
                            [
                                'attribute' => 'ปี',
                                'label' => 'ปี พ.ศ.',
                                'pageSummary' => 'รวมทั้งหมด',
                            ],
							[
                                'attribute' => 'Visit',
                                'label' => 'จำนวนครั้ง',
                                'contentOptions' => ['class' => 'text-center'],  // จัดตำแหน่งตัวเลขให้ตรงกลาง
                                'pageSummary' => true,
                            ],
                            
                                [
                                    'attribute' => 'amount',
                                    'label' => 'จำนวนครั้ง',
                                    'pageSummary' => true, // แสดงผลรวมของจำนวนครั้ง
                                    'format' => ['integer'], // จัดรูปแบบเป็นตัวเลข
                                ],
                            ],
                        ]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>


        <!-- แยกตามแผนก -->
        <div class="col-md-4 mb-4 d-flex align-items-stretch">
            <div class="card flex-fill">
                <div class="card-body">
                   <div class="well">
                   <h5 class="card-title text-primary mb-3">
     *****รายงานแยกตามแผนกบริการ  ******
    <?php if (!empty($departmentName)): ?>
        
    <?php endif; ?>วันที่เริ่มต้น: <?= $startDate ?>----วันที่สิ้นสุด: <?= $endDate ?>
</h5>
                        <div class="report-table">
                             <?= GridView::widget([
                        'dataProvider' => $depProvider,
                        'showPageSummary' => true, // เปิดการแสดงผลรวม
                        'layout' => "{items}",
                        'columns' => [
                            ['class' => 'kartik\grid\SerialColumn'],
                            [
                                'attribute' => 'unit_id',
                                'label' => 'รหัสแผนก',
                            ],
                            [
                                'attribute' => 'unit_name',
                                'label' => 'แผนก',
                                'pageSummary' => 'รวมทั้งหมด',
                            ],
                            [
                                'attribute' => 'amount',
                                'label' => 'จำนวน',
                                'pageSummary' => true, // แสดงผลรวมของจำนวน
                                'format' => ['integer'], // จัดรูปแบบเป็นตัวเลข
                            ],
                        ],
                    ]); ?>
                        </div>
                    </div>
                </div>
            </div>
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
                                   'format' => ['datetime', 'php:d/m/Y H:i:s'],
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
                                    'attribute' => 'sex',
                                    'label' => 'เพศ',
                                    'contentOptions' => ['class' => 'text-center'],
                                ],
                                [
                                    'attribute' => 'age',
                                    'label' => 'อายุ',
                                    'contentOptions' => ['class' => 'text-center'],
                                ],
								[
                                    'attribute' => 'diag',
                                    'label' => 'รหัสโรค',
                                    'contentOptions' => ['class' => 'text-center'],
                                ],
                                [
                                    'attribute' => 'BMI',
                                    'label' => 'BMI',
                                ],
                                [
                                    'attribute' => 'HbA1c',
                                    'label' => 'LAB',
                                ],
                                [
                                    'attribute' => 'hosp_name',
                                    'label' => 'หน่วยงาน',
                                ],
								[
                                    'attribute' => 'hosp_id',
                                    'label' => 'รหัสหน่วยงาน',
                                ],
								[
                                    'attribute' => 'บ้าน',
                                    'label' => 'บ้าน',
                                ],
								[
                                    'attribute' => 'ตำบล',
                                    'label' => 'ตำบล',
                                ],
								[
                                    'attribute' => 'อำเภอ',
                                    'label' => 'อำเภอ',
                                ],
								[
                                    'attribute' => 'จังหวัด',
                                    'label' => 'จังหวัด',
                                ],
								[
                                    'attribute' => 'claimcode',
                                    'label' => 'authen',
                                    'contentOptions' => ['class' => 'text-center  text-primary'],
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
