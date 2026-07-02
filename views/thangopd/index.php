<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;

$this->title = 'รายงานบริการต่างด้าวผู้ป่วยนอก';
?>
<style>
/* General Layout */
body {
    background-color: #f1f5f8; /* สีพื้นหลังสบายตา */
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 0;
}

/* Card Styling */
.custom-card {
    border-radius: 10px; /* มุมโค้ง */
    border: 1px solid #ddd;
    box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1); /* เงานุ่มๆ */
    background-color: #fff;
    padding: 20px;
    transition: all 0.3s ease;
}

.custom-card:hover {
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2); /* เพิ่มเงาเมื่อ Hover */
    transform: translateY(-5px); /* เคลื่อนที่ขึ้น */
}

/* Form Controls */
.form-label {
    font-weight: bold;
    color: #333;
}

.form-control {
    border-radius: 5px;
    border: 1px solid #ddd;
    padding: 10px;
    font-size: 1rem;
    transition: border 0.3s ease;
}

.form-control:focus {
    border-color: #28a745; /* สีเขียวเข้มเมื่อ focus */
    box-shadow: 0 0 5px rgba(40, 167, 69, 0.5); /* เงาเมื่อ focus */
}

/* Buttons */
.btn-success {
    background-color: #28a745;
    border-color: #28a745;
    color: white;
    padding: 12px;
    font-size: 1rem;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

.btn-success:hover {
    background-color: #218838; /* เปลี่ยนสีเมื่อ hover */
    border-color: #1e7e34;
}

/* Table Styling */
table {
    width: 100%;
    margin-top: 20px;
    background-color: #fff;
    border-collapse: collapse;
}

th, td {
    padding: 12px;
    text-align: center;
    border: 1px solid #ddd;
    font-size: 1rem;
}

th {
    background-color: #007bff;
    color: white;
}

tr:nth-child(even) {
    background-color: #f9f9f9;
}

tr:hover {
    background-color: #f1f1f1;
}

/* Responsive Design */
@media (max-width: 768px) {
    .col-xl-3, .col-xl-9 {
        width: 100%;
        margin-bottom: 20px;
    }
}
/* ปรับขนาดฟอนต์ของตารางทั้งหมดใน GridView */
.grid-view table {
    font-size: 1.2rem; /* ขยายขนาดตัวอักษรในตาราง */
}

/* ปรับขนาดฟอนต์ของคอลัมน์ใน GridView */
.grid-view th, .grid-view td {
    font-size: 1.2rem; /* ขยายขนาดตัวอักษรในหัวข้อและเซลล์ */
}


</style>

   
    <div class="row">
        <!-- Section for Date Filters -->
        <div class="col-xl-3 col-md-4">
            <div class="card border-primary shadow-lg h-100 py-2">
                <div class="card-body">
                    <h5 class="card-title text-primary mb-3">ช่วงวันที่</h5>
                    <?php $form = ActiveForm::begin([
                        'method' => 'get',
                        'action' => ['index'],
                    ]); ?>

                    <div class="form-group">
                        <?= Html::label('วันที่เริ่มต้น', 'start_date', ['class' => 'form-label font-weight-bold']) ?>
                        <?= Html::input('date', 'start_date', Yii::$app->request->get('start_date', '2023-01-01'), [
                            'class' => 'form-control border-primary',
                        ]) ?>
                    </div>

                    <div class="form-group">
                        <?= Html::label('วันที่สิ้นสุด', 'end_date', ['class' => 'form-label font-weight-bold']) ?>
                        <?= Html::input('date', 'end_date', Yii::$app->request->get('end_date', '2024-09-30'), [
                            'class' => 'form-control border-primary',
                        ]) ?>
                    </div>

                    <div class="form-group text-center">
                        <?= Html::submitButton('<i class="fas fa-search"></i> ค้นหา', ['class' => 'btn btn-primary btn-block']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>

        <!-- Section for Data Display -->
        <div class="col-xl-9 col-md-8">
            <div class="card shadow-lg h-100 py-2">
                <div class="card-body">
                    <h5 class="card-title text-primary mb-3">ผลลัพธ์รายงานบริการผู้ป่วยนอก</h5>
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'tableOptions' => ['class' => 'table table-bordered table-hover table-striped'],
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            [
                                'attribute' => 'ปีงบ',
                                'label' => 'ปีงบ',
                                'contentOptions' => ['class' => 'text-center'],
                            ],
                            [
                                'attribute' => 'จำนวนคน',
                                'label' => 'จำนวนคน',
                                'contentOptions' => ['class' => 'text-center'],
                            ],
                            [
                                'attribute' => 'จำนวนครั้ง',
                                'label' => 'จำนวนครั้ง',
                                'contentOptions' => ['class' => 'text-center'],
                            ],
                            [
                                'attribute' => 'สัญชาติ',
                                'label' => 'สัญชาติ',
                            ],
                            [
                                'attribute' => 'ค่ารักษารวม',
                                'label' => 'ค่ารักษารวม',
                                'format' => ['decimal', 2],
                                'contentOptions' => ['class' => 'text-right text-success'],
                            ],
                            [
                                'attribute' => 'เรียกเก็บ',
                                'label' => 'เรียกเก็บ',
                                'format' => ['decimal', 2],
                                'contentOptions' => ['class' => 'text-right text-primary'],
                            ],
                            [
                                'attribute' => 'เรียกเก็บไม่ได้',
                                'label' => 'เรียกเก็บไม่ได้',
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

    <hr>

    <!-- GridView สำหรับข้อมูลแยกตามคน -->
    <div class="card mt-3 border-success shadow-lg">
        <div class="card-header bg-success text-white">
            <h4 class="m-0">ข้อมูลแยกตามคน</h4>
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
                        'attribute' => 'reg_datetime',
                        'label' => 'วันที่รับบริการ',
                        'format' => ['date', 'php:d/m/Y'],
                        'contentOptions' => ['class' => 'text-center'],
                    ],
                    [
                        'attribute' => 'unit_name',
                        'label' => 'แผนก',
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
                        'attribute' => 'NATN_NAME',
                        'label' => 'สัญชาติ',
                    ],
                    [
                        'attribute' => 'total',
                        'label' => 'ค่ารักษารวม',
                        'format' => ['decimal', 2],
                        'contentOptions' => ['class' => 'text-right text-success'],
                    ],
                    [
                        'attribute' => 'paid',
                        'label' => 'เรียกเก็บ',
                        'format' => ['decimal', 2],
                        'contentOptions' => ['class' => 'text-right text-primary'],
                    ],
                    [
                        'attribute' => 'no_claim',
                        'label' => 'เรียกเก็บไม่ได้',
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
