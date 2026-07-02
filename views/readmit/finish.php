<?php
use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\widgets\ActiveForm;
use yii\jui\DatePicker;

$this->title = "ER-สิ้นสุดบริการ";

// ตั้งค่าค่าวันเริ่มต้น/สิ้นสุด ถ้าไม่ได้ส่งมาจาก controller
$date1 = isset($date1) ? $date1 : date('Y-m-d');
$date2 = isset($date2) ? $date2 : date('Y-m-d');
?>

<style>
    .modern-container {
        width: 80%;
        margin: auto;
        background: linear-gradient(145deg, #e1bfff, #f9e6ff); /* สดใสขึ้น */
        border-radius: 25px;
        padding: 30px;
        box-shadow: 0 8px 20px rgba(155, 0, 255, 0.3), inset 0 0 12px rgba(255, 255, 255, 0.4);
    }

    .modern-title {
        text-align: center;
        font-size: 2rem;
        color: #8000ff; /* ปรับให้ม่วงสด */
        font-weight: bold;
        margin-bottom: 25px;
    }

    .form-label {
        color: #8000ff; /* ปรับให้ม่วงสด */
        font-weight: bold;
        margin-right: 10px;
    }

    .form-date {
        border-radius: 12px;
        padding: 10px 15px;
        border: 2px solid #ce93d8; /* ม่วงอ่อน */
        background-color: #ffffff;
        box-shadow: 0 0 8px rgba(155, 0, 255, 0.1);
        font-size: 1.1rem;
        margin-right: 10px;
    }

    .submit-btn {
        border-radius: 12px;
        background-color: #ba68c8; /* ปรับให้สด */
        color: white;
        font-weight: bold;
        padding: 10px 20px;
        font-size: 1.2rem;
        box-shadow: 0 5px 12px rgba(186, 104, 200, 0.5);
        transition: 0.3s;
    }

    .submit-btn:hover {
        background-color: #d05ce3; /* สีม่วงเข้มตอน hover */
        color: #fff;
    }

    .table-header-custom th {
        background-color: #ab47bc !important;
        color: white !important;
        text-align: center;
        font-weight: bold;
    }

    .table-hover tbody tr:nth-child(even) {
        background-color: #f3e5f5;
    }

    .table-hover tbody tr:nth-child(odd) {
        background-color: #ffffff;
    }

    .table-hover tbody tr:hover {
        background-color: #e1bee7 !important;
    }

    .scrollable-table-container {
        max-height: 600px;
        overflow-y: auto;
        border-radius: 12px;
        border: 2px solid #ce93d8;
        box-shadow: inset 0 0 5px rgba(0, 0, 0, 0.1);
        padding: 10px;
        margin-top: -80px; /* เลื่อนขึ้นประมาณ 5 บรรทัด */
        background-color: #ffffff;
    }

    .scrollable-table-container thead th {
        position: sticky;
        top: 0;
        background-color: #8e24aa !important;
        color: white;
        z-index: 2;
        text-align: center;
    }

    .sidebar,
    .main-sidebar,
    .sidebar-menu {
        display: none !important;
    }

    .content-wrapper,
    .content {
        margin-left: 0 !important;
    }

    @media print {
        body * {
            visibility: hidden;
        }

        #print-area, #print-area * {
            visibility: visible;
        }

        #print-area {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }

        button {
            display: none !important;
        }
    }
</style>




<!-- ✅ หัวเรื่อง -->
<h3 class="modern-title">
    <i class="fas fa-ambulance"></i> รายงานผู้ป่วย <span class="text-danger">ER - สิ้นสุดบริการ</span>
</h3>

<!-- ✅ กล่องเลือกวันที่ -->
<div class="modern-container">
    <?php $form = ActiveForm::begin([
        'method' => 'POST',
        'action' => ['readmit/finish'],
    ]); ?>

    <div class="form-group d-flex justify-content-center align-items-center flex-wrap">
        <label class="form-label"><i class="far fa-calendar-alt"></i> วันที่:</label>
        <?= DatePicker::widget([
            'name' => 'date1',
            'value' => $date1,
            'language' => 'th',
            'clientOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd',
                'todayHighlight' => true,
            ],
            'options' => ['class' => 'form-date', 'placeholder' => 'เริ่ม'],
        ]) ?>

        <label class="form-label">ถึง:</label>
        <?= DatePicker::widget([
            'name' => 'date2',
            'value' => $date2,
            'language' => 'th',
            'clientOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd',
                'todayHighlight' => true,
            ],
            'options' => ['class' => 'form-date', 'placeholder' => 'สิ้นสุด'],
        ]) ?>

        <?= Html::submitButton('<i class="fas fa-search"></i> ค้นหา', ['class' => 'submit-btn']) ?>
		<button type="button" class="btn btn-primary btn-modern" onclick="printGrid()">🖨️ Print Results</button>
    </div>
	
    <?php ActiveForm::end(); ?>
</div>

<!-- ✅ GridView -->
<br>

<div class="modern-container">
     <div id="print-area" class="scrollable-table-container">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
           /// 'summary' => 'แสดงทั้งหมด {totalCount} รายการ',
		    'summary' => false, // << ซ่อนข้อความ “แสดงทั้งหมด xx รายการ”
            'hover' => true,
            'bordered' => true,
            'striped' => true,
            'responsive' => false,
            'responsiveWrap' => false,
            'floatHeader' => false,
            'headerRowOptions' => ['class' => 'table-header-custom'],
            'tableOptions' => ['class' => 'table table-bordered table-striped table-hover text-center'],
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'HN',
                'REG_DATETIME',
                'ชื่อ สกุล',
                'ICD10_TM',
                'หัตถการ',
                'แจ้งหนี้',
                'แผนกลงทะเบียน',

                [
                    'attribute' => 'finish',
                    'label' => 'แผนกสิ้นสุดบริการ',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return Html::encode($model['finish']);
                    },
                    'contentOptions' => function ($model) {
                        $finish = trim($model['finish']);
                        return [
                            'style' => $finish !== 'สิ้นสุดบริการ' ? 'color: red; font-weight: bold;' : '',
                        ];
                    },
                ],

                'INSCL_claim',
                'ระยะเวลารอคอย',
            ],
        ]); ?>
    </div>
	 <p>
    <?= Html::a('⏪ กลับหน้าหลัก', ['referopd/index3'], [
        'class' => 'btn btn-custom'
    ]) ?>
</p>

<?php
$this->registerCss("
    .btn-custom {
        background-color: #3399ff; /* สีฟ้า */
        color: white;
        padding: 8px 16px;
        border-radius: 6px;
        border: none;
        font-weight: bold;
        transition: background-color 0.3s;
    }
    .btn-custom:hover {
        background-color: #007acc; /* ฟ้าเข้มตอน hover */
        color: white;
        text-decoration: none;
    }
");
?>


</div>
<script>
function printGrid() {
    var printContents = document.getElementById("print-area").innerHTML;
    var originalContents = document.body.innerHTML;

    document.body.innerHTML = "<html><head><title>Print</title></head><body>" + printContents + "</body>";
    window.print();
    document.body.innerHTML = originalContents;
    location.reload(); // reload เพื่อเรียก DOM เดิมกลับ
}
</script>
  <p>
    