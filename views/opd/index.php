<?php
use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\widgets\ActiveForm;
use yii\jui\DatePicker;
use miloschuman\highcharts\Highcharts;
use yii\helpers\Json;
use yii\bootstrap\Modal;
//use kartik\export\ExportMenu;

$this->title = 'รายงานบริการผู้ป่วยนอก  แพทย์แผนไทย บริการนวด 9007810';
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

<div class="container-fluid">
    <div class="row">
        <!-- 10 อันดับโรค -->
        <div class="col-md-5 mb-4 d-flex align-items-stretch">
            <div class="card flex-fill">
                <div class="card-body">
                    <div class="well">
                        <?= GridView::widget([
                            'dataProvider' => $diagProvider,
							#'showPageSummary' => true, // เปิดการแสดงผลรวม
                            'layout' => "{items}", 
                            'columns' => [
                                  ['class' => 'kartik\grid\SerialColumn'],
                                [
                                    'attribute' => 'diag',
                                    'label' => 'รหัสโรค',
                                ],
                                [
                                    'attribute' => 'icd_name',
                                    'label' => 'รายละเอียดโรค',
                                ],
								[
                                    'attribute' => 'icd_thai',
                                    'label' => 'ชื่อโรคไทย',
                                ],
                                [
                                    'attribute' => 'amount',
                                    'label' => 'จำนวน',
                                    'contentOptions' => ['class' => 'text-center'],
                                ],
                            ],
                        ]); ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- สิทธิ์การรักษาแสดงข้อมูล -->
        
<div class="col-md-5 mb-4 d-flex align-items-stretch">
    <div class="card flex-fill">
        <div class="card-body">
            <div class="well">
                <div class="report-table">
                    <?= GridView::widget([
                        'dataProvider' => $insclProvider,
                        'showPageSummary' => true, // เปิดการแสดงผลรวม
                        'layout' => "{items}",
                        'columns' => [
                            ['class' => 'kartik\grid\SerialColumn'],

                            [
                                'attribute' => 'INSCL_NAME',
                                'label' => 'Insurance Name',
                                'pageSummary' => 'รวมทั้งหมด',
                            ],
                            [
                                'attribute' => 'Visit',
                                'label' => 'จำนวนครั้ง',
                                'pageSummary' => true,
                                'format' => ['integer'], // จัดรูปแบบเป็นตัวเลข
                            ],
                            [
                                'attribute' => 'amount',
                                'label' => 'จำนวนคน',
                                'pageSummary' => true,
                                'format' => ['integer'], // จัดรูปแบบเป็นตัวเลข
                            ],
                        ],
                       
                        'toolbar' => false, // ปิดเครื่องมือด้านบน
                        'striped' => true,
                        'hover' => true,
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
	
<div class="row">
	<!-- รายงานการผู้สั่งทำหัตถการ -->
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
                                    'attribute' => 'staff_id',
                                    'label' => 'รหัสบุคคล',  
                                ],
                                [
                                    'attribute' => 'STAFF_fname',
                                    'label' => 'ผู้สั่งหัตถการนวด',
                                    'pageSummary' => 'รวมทั้งหมด',
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
    <!-- รายงานการทำหัตถการ -->
    <div class="col-md-4 mb-4 d-flex align-items-stretch">
        <div class="card flex-fill">
            <div class="card-body">
                <div class="well">
                    <div class="report-table">
                        <?= GridView::widget([
                            'dataProvider' => $surgeonProvider,
                            'showPageSummary' => true, // เปิดการแสดงผลรวม
                            'layout' => "{items}",
                            'columns' => [
                                ['class' => 'kartik\grid\SerialColumn'],
                                [
                                    'attribute' => 'surgeon_id',
                                    'label' => 'รหัสบุคคล',  
                                ],
                                [
                                    'attribute' => 'SURGEON_fname',
                                    'label' => 'ผู้ทำหัตถการนวด',
                                    'pageSummary' => 'รวมทั้งหมด',
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
                                'label' => 'จำนวนคน',
                                'contentOptions' => ['class' => 'text-center'],  // จัดตำแหน่งตัวเลขให้ตรงกลาง
                                'pageSummary' => true,
                            ],
                            [
                                'attribute' => 'refers',
                                'label' => 'refer',
                                'contentOptions' => ['class' => 'text-center'],  // จัดตำแหน่งตัวเลขให้ตรงกลาง
                                'pageSummary' => true,
                            ],
                            
                            [
                                'attribute' => 'admit',
                                'label' => 'admit',
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
                                    'attribute' => 'HN',
                                    'label' => 'HN',
                                    'contentOptions' => ['class' => 'text-center'],
                                ],
                                [
                                    'attribute' => 'REGDATE',
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
                                    'attribute' => 'diag',
                                    'label' => 'รหัสโรค',
                                    'contentOptions' => ['class' => 'text-center'],
                                ],
                                [
                                    'attribute' => 'CODE',
                                    'label' => 'รหัสนวด',
                                ],
                                [
                                    'attribute' => 'NICKNAME',
                                    'label' => 'หัตถการ',
                                ],
                                [
                                    'attribute' => 'STAFF_fname',
                                    'label' => 'ผู้สั่ง',
                                ],
								[
                                    'attribute' => 'SURGEON_fname',
                                    'label' => 'ผู้นวด',
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
