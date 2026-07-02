<?php

use kartik\grid\GridView;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use yii\helpers\Url;
use yii\data\ArrayDataProvider;
use yii\web\View;
//use common\models\RContributionIpd;
use yii\data\ActiveDataProvider;
//use yii\bootstrap4\Alert;
use yii\bootstrap\Modal;



$this->title = 'ข้อมูลการปิดสิทธิ์ทังหมด';
//$this->params['breadcrumbs'][] = ['label' => 'รายงาน', 'url' => ['thaimed/index']];
$this->registerCss('
    .log-line {
        padding: 5px;
    }
');
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modal Example</title>
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@iconify/iconify@latest/dist/iconify.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<!-- ####### สลับสี ###################################### -->
<style>
    /* กำหนดสีให้กับแถวที่เป็นเลขคี่ */
    .my-striped-table tr:nth-child(odd) {
        background-color: #efefef;
        /* สีเทาจาง ๆ */
    }

    /* กำหนดสีให้กับแถวที่เป็นเลขคู่ */
    .my-striped-table tr:nth-child(even) {
        background-color: white;
    }
</style>
<style>
    .custom-hover tbody tr:hover {
        background-color: #f5f5f5; /* สีที่ต้องการเมื่อ hover */
    }
</style>
<!-- ### ตัวอักษรกระพริบ  ##### -->
<style>
    .btn-blink {
        animation: blink-animation 1s infinite;
    }

    @keyframes blink-animation {
        0% { opacity: 1; }
        50% { opacity: 0; }
        100% { opacity: 1; }
    }
</style>
<!-- <div class="well"> -->
<!-- <div style="background-color: #126F30; padding: 10px; border: 0px solid #D4F1F5; box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.3);">
        <font color="white" size="5"><i class='fas fa-bed'></i> ผู้ป่วยนอก UCS 16 แฟ้ม [FDH Telemed]</font>
        <div style="display: flex; justify-content: flex-end;">
            <span style="color: yellow;">jhcisdb = db14j(200.14) โรงพยาบาลม่วงสามสิบ</span>
        </div>
    </div> -->
<!-- <h5 style="color: green; box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.1); padding: 10px;"><i class="fas fa-user"></i> ข้อมูล 16 แฟ้มส่ง Finacail Data Hub </h5> -->

<body>
    <!--     
    <script type="text/javascript">
    setTimeout("frmMain.submit();",50000);
    //5000 อยากได้เร็วก็กำหนดตัวเลขน้อยๆเอานะ
    </script> -->
    <style>
        /* CSS class with light green background */
        .visit-element {
            background-color: lightgreen;
            padding: 5px;
            /* Optional padding for spacing */
            margin-bottom: 5px;
            /* Optional margin for spacing */
        }
    </style>
    <style>
        .panel-custom {
            background-color: #2f1c00;
            /* สีน้ำตาลเข้ม */
        }

        .panel-custom .panel-heading {
            color: #00aaff;
            /* ตัวหนังสือสีฟ้า */
        }

        .panel-custom .panel-body {
            color: #00aaff;
            /* ตัวหนังสือสีฟ้า */
        }
    </style>
    <style>
        .panel-custom {
            max-height: 200px;
            /* กำหนดขนาดสูงสุดของพาแนล */
            overflow-y: auto;
            /* ให้แสดงแถบเลื่อนเมื่อเนื้อหาเกินขนาดที่กำหนด */
        }

        .panel-body {
            padding: 10px;
            /* กำหนดระยะห่างของเนื้อหาภายในพาแนล */
        }
    </style>
    <style>
        .custom-spinner {
            border: 16px solid #f3f3f3;
            /* Light grey */
            border-top: 16px solid purple;
            /* Purple */
            border-radius: 50%;
            width: 120px;
            height: 120px;
            animation: spin 1s linear infinite;
        }

        .code-block {
            font-family: "Courier New", Courier, monospace; // ฟอนต์สำหรับโค้ด
            background-color: #f5f5f5; // สีพื้นหลังอ่อน
            padding: 10px; // เพิ่มระยะห่างภายใน
            border: 1px solid #ddd; // ขอบบางๆ
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
	<style>
        .info-card {
            background: linear-gradient(to right, #d9f99d, #a7f3d0); /* Gradient from light green to a bit darker green */
            box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.3); /* Card shadow */
            color: #000; /* Text color */
            padding: 20px; /* Padding inside the card */
            border-radius: 10px; /* Rounded corners */
            font-size: 16px; /* Font size */
        }
    </style>
    <style>
        #loading-spinner {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            display: none;
        }
    </style>
	<style>
.my-striped-table tbody tr:hover {
    background-color: rgba(144, 238, 144, 0.5); /* สีเขียวอ่อนที่โปร่งใส */
}
</style>
    <!-- ############################# แสดงปุ่ม Select All  ################################################################### -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script language="JavaScript">
        function ClickCheckAll(vol) {

            var i = 1;
            for (i = 1; i <= document.frmMain.hdnCount.value; i++) {
                if (vol.checked == true) {
                    eval("document.frmMain.chkDel" + i + ".checked=true");
                } else {
                    eval("document.frmMain.chkDel" + i + ".checked=false");
                }
            }
        }
    </script>
    <!-- ############################# แสดงปุ่ม Select All  ################################################################### -->
    <script>
        function ClickCheckAll(vol) {
            // สมมติว่า frmMain เป็นฟอร์มหลัก
            var checkboxes = document.frmMain.querySelectorAll('input[name="chkDel[]"]');
            checkboxes.forEach(function(checkbox) {
                checkbox.checked = vol.checked; // เลือก/ยกเลิกการเลือกตามช่องหลัก
            });
        }
    </script>
    <!-- ############################# จบแสดงปุ่ม Select All  ################################################################### -->
   
    <div class="row">
        <div class="col-xl-3 col-md-3 mb-3">
            <div class="info-card bg-info" style="box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.3);">
                <span class="info-box-icon"><i class="far fa-calendar-check" style="color: green;"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text" style="color: green; font-size: 18px;">รายการผ่านวันนี้</span>
                    <span class="info-box-number"><?php echo $amount ?></span>
                    <!-- <span class="info-box-number">100</span> -->
                </div>

                <?php
                Modal::begin([
                    'id' => 'myModal',
                    'header' => '<h4>File List</h4>',
                    'size' => Modal::SIZE_LARGE,
                ]);
                ?>
                <div id="modal-content">Loading...</div> <!-- เนื้อหาของ modal จะถูกโหลดผ่าน Ajax -->
                <?php Modal::end(); ?>
                <?php
                $this->registerJs("
                        $('#myModal').on('show.bs.modal', function (event) {
                            var button = $(event.relatedTarget);
                            var url = button.data('url');
                            var modal = $(this);

                            $.ajax({
                                url: url,
                                success: function(data) {
                                    modal.find('#modal-content').html(data); // แสดงเนื้อหาใน Modal
                                }
                            });
                        });
                    ");
                ?>
                <div style="text-align: right;">
                    <div style="text-align: right;">
                        <?php
                        echo Html::a('เปิดอ่านไฟล์', '#', [
                            'class' => 'btn btn-primary',
                            'data-toggle' => 'modal',
                            'data-target' => '#myModal', // เปิด Modal
                            'data-url' => \yii\helpers\Url::to(['closeall/list-files-partial']), // URL สำหรับโหลดเนื้อหา
                        ]);
                        ?>
                        <?= Html::a('<i class="fa fa-check-square" aria-hidden="true"></i> ผ่าน', null, ['class' => 'btn btn-success', 'id' => 'link1']) ?>
                        
                    </div>
                </div>
                </a>
            </div>
            <!-- /.info-box -->
        </div>

        <div class="col-xl-3 col-md-3 mb-3">
            <div class="info-card bg-warning" style="box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.3);">
                <span class="info-box-icon"><i class="fa-sharp fa-solid fa-compass" style="color: red;"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text" style="color: red; font-size: 14px;">จัดการ Token</span>
                    <span class="info-box-number"> 0</span>

                </div>

               <div style="text-align: right;">
                <a href="<?= Url::to(['closeall/run-curl']) ?>" class="btn btn-info" style="font-size: 16px;">
                                    Token-Pgans <i class="fa fa-arrow-circle-right"></i>
                                </a>
						

                    <?= Html::a('<i class="fa fa-ban" aria-hidden="true"></i> ไม่ผ่าน', null, ['class' => 'btn btn-danger', 'id' => 'link2']) ?>
                    
                </div>
                </a>
            </div>
        </div>


        <div class="col-xl-3 col-md-3 mb-3">
            <div class="info-card bg-warning" style="box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.3);">
                <span class="info-box-icon"><i class="fas fa-hand-holding-heart" style="color: orange;"></i></i></span>
                <div class="info-box-content">

                    <span class="info-box-number" style="color: green; font-size: 18px;">ลิงค์เข้าเว็บ FDH</span>

                    <a href="https://uat-fdh.inet.co.th/hospital/detail" target="_blank">FDH-UAT</a>--
                    <a href="https://fdh.moph.go.th/hospital/" target="_blank">FDH-Production</a><br>
                    
                    
                    <!-- ลิงก์ที่เรียกใช้ JavaScript เพื่อเปิดป๊อปอัป -->
                    <!-- <div class="text-center">
                            <a href="javascript:void(0);" id="exportExcelButton" class="btn btn-primary" style="font-size: 16px; border: 4px solid #91ffff;" onclick="openPopup();">
                                รายชื่อและส่งออก ExcelFiles <i class="fa fa-arrow-circle-right"></i>
                            </a>
                        </div> -->

                    <script>
                        function openPopup() {
                            const url = "<?= \yii\helpers\Url::to(['closeall/exportexcel']); ?>"; // ลิงก์ไปยังไฟล์ Excel
                            const popupWindow = window.open(url, 'ExcelPopup', 'width=600,height=400'); // สร้างหน้าต่างป๊อปอัป
                            popupWindow.focus(); // โฟกัสไปยังป๊อปอัป
                        }
                    </script>


                    <a href="<?= \yii\helpers\Url::to(['fdhhurb/index']) ?>" class="btn btn-warning modalLink" style="font-size: 16px;" target="_blank">
                        Query <i class="fa fa-arrow-circle-right"></i>
                    </a>
                       <?= Html::a('Export', ['closeall/exports'], ['class' => 'btn btn-success']) ?>
					   
                    
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-3 mb-3">
            <div class="info-card bg-info" style="box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.3); width: 350px; height: 140px;">

            <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-3">
        <?php $form = ActiveForm::begin(['action' => ['closeall/index']]); ?>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label text-right">วันที่:</label>
                <div class="col-sm-9">
                    <?= yii\jui\DatePicker::widget([
                        'name' => 'date1',
						'value' => Yii::$app->request->post('date1', date('Y-m-d')),
                        //'value' => $date1,
                        'language' => 'th',
                        'dateFormat' => 'yyyy-MM-dd',
                        'options' => [
                            'class' => 'form-control',
                            'placeholder' => 'เลือกวันที่เริ่มต้น',
                        ],
                        'clientOptions' => [
                            'changeMonth' => true,
                            'changeYear' => true,
                        ],
                    ]); ?>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-3 col-form-label text-right">ถึง:</label>
                <div class="col-sm-9">
                    <?= yii\jui\DatePicker::widget([
                        'name' => 'date2',
						'value' => Yii::$app->request->post('date2', date('Y-m-d')),
                        //'value' => $date2,
                        'language' => 'th',
                        'dateFormat' => 'yyyy-MM-dd',
                        'options' => [
                            'class' => 'form-control',
                            'placeholder' => 'เลือกวันที่สิ้นสุด',
                        ],
                        'clientOptions' => [
                            'changeMonth' => true,
                            'changeYear' => true,
                        ],
                    ]); ?>
					<button class="btn btn-danger">ตกลง</button>
                </div>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

            </div>
        </div>
    </div>
	
<div class="card">
    <div class="card-header bg-primary text-white">
       
    </div>
   <div class="card-body">
    <button id="read-card" class="btn btn-success" 
        style="background-color: #0099a4; border: 4px solid #dadada; padding: 10px 20px; border-radius: 30px; 
               box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); font-size: 2.2rem; text-transform: uppercase; cursor: pointer; width: auto;">
        📟 อ่านบัตรประจำตัวประชาชนสำหรับเจ้าหน้าที่
    </button>
    <div id="result" class="mt-3"></div>
</div>


<?php
$url = Url::to(['closeall/read-smart-card']);
$js = <<<JS
    $('#read-card').on('click', function () {
        $('#result').html('<p class="text-warning">กำลังอ่านข้อมูล...</p>');

        $.ajax({
            url: '{$url}',
            method: 'GET',
            success: function(response) {
                if (response.status === 'success') {
                    let fullContent = `
                        <h5 class="text-success">✅ อ่านข้อมูลสำเร็จ</h5>
                        <p><strong>เลขบัตรประชาชน:</strong> \${response.pid}</p>
                        <p><strong>ชื่อ:</strong>  \${response.fname} \${response.lname}</p>
                        <p><strong>วันเกิด:</strong> \${response.birthdate} (อายุ \${response.age} ปี)</p>
                        <p><strong>สิทธิหลัก:</strong> \${response.maininscl}</p>
                        <p><strong>สิทธิรอง:</strong> \${response.subinscl}</p>
                        <p><strong>Correlation ID:</strong> \${response.correlationId}</p>
                    `;

                    let nameOnly = `<p><strong>ชื่อ:</strong>  \${response.fname} \${response.lname} <strong>เลขบัตรประชาชน:</strong> \${response.pid}`;

                    $('#result').html(fullContent);

                    // ตั้งเวลาลบข้อมูลอื่นภายใน 10 วินาที
                    setTimeout(() => {
                        $('#result').html(nameOnly);
                    }, 10000);
                } else {
                    $('#result').html('<p class="text-danger">❌ ' + response.message + '</p>');
                }
            },
            error: function(xhr, status, error) {
                $('#result').html('<p class="text-danger">❌ ไม่สามารถติดต่อเซิร์ฟเวอร์ได้ (' + error + ')</p>');
            }
        });
    });
JS;
$this->registerJs($js, View::POS_READY);
?>

    <!-- ############################################ Grid View ######################################################################## -->
    
 <?= Html::beginForm(['closeall/check'], 'post', ['name' => 'frmMain', 'id' => 'frmMain']); ?>


<div style="overflow: auto; height: 600px; border: 1px solid #ddd;">
    <table class="table my-striped-table" width="1000" border="0" bordercolor="#ddd" 
        style="border-collapse: collapse; box-shadow: 2px 2px 5px rgba(0,0,0,0.2);">
        <thead style="position: sticky; top: 0; background-color: #fff; z-index: 1;">
            <tr>
                <th>#</th>
                <th>วันที่</th>
                <th>เลขบริการ</th>
                <th>cid</th>
                <th>Authen</th>
                <th>Enpoint</th>
                <th>Hn</th>
                <th>ชื่อ-สกุล</th>
                <th>อายุ</th>
                <th>แผนก</th>
				<th>รหัสโรค</th>
				<th>เบอร์โทร</th>
                <th>สิทธิ์</th>
                <th>ค่ารักษา</th>
                <th>ปิดสิทธิ์</th>
                <th>ดึงข้อมูล</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($visitProvider && $visitProvider->getCount() > 0): ?>
                <?php foreach ($visitProvider->getModels() as $key => $value) : ?>
                    <tr>
                        <td><?= $value["No"]; ?></td>
                        <td><?= $value["regdate"]; ?></td>
                        <td><?= $value["visit"]; ?></td>
                        <td><?= $value["cid"]; ?></td>
                        <td style="color: orange;"><?= $value["claimcode"]; ?></td>
                        <td style="color: green;"><?= $value["enpoint"]; ?></td>
                        <td><?= $value["hn"]; ?></td>
                        <td><?= $value["fullname"]; ?></td>
                        <td><?= $value["age"]; ?></td>
                        <td><?= $value["unit_name"]; ?></td>
						<td><?= $value["icd10_tm"]; ?></td>
						<td><?= $value["telephone"]; ?></td>
                        <td><?= $value["inscl_name"]; ?></td>
                        <td><?= $value["amount"]; ?></td>
						<td>
    <!-- แทรกค่า visit ใน hiddenInput -->
    <?= Html::beginForm(['closeall/check1'], 'post', ['id' => 'fdhForm1']) ?>
        <?= Html::hiddenInput('visit', $value["visit"], ['id' => 'visitInput1']); ?>
        
        <!-- ปุ่ม submit ส่งข้อมูลไปยัง API actionCheck -->
        <?= Html::submitButton('authen', [
            'class' => 'btn btn-danger', 
            'style' => 'background-color: #ab29c2; border: 4px solid #dadada; padding: 10px 20px; border-radius: 30px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); font-size: 1.2rem; text-transform: uppercase; cursor: pointer; width: auto;',
        ]); ?>
    <?= Html::endForm(); ?>
</td>

						<td>
    <!-- ปุ่ม GET สำหรับ API actionCheckNhso -->
    <?= Html::a('GETAuth', ['closeall/check-nhso1', 'cid' => $value['cid'], 'visit_id' => $value['visit'], 'telephone' => $value['telephone']], [
        'class' => 'btn btn-danger reset-values',
        'style' => 'background-color: #0ba8bd; border: 4px solid #dadada; padding: 10px 20px; border-radius: 30px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); font-size: 1.2rem; text-transform: uppercase; cursor: pointer; width: auto;',
        'onclick' => 'resetValues()' // เรียกใช้งานฟังก์ชัน JavaScript
    ]) ?>
</td>
						 <td>
    <!-- แทรกค่า visit ใน hiddenInput -->
    <?= Html::beginForm(['closeall/check'], 'post', ['id' => 'fdhForm']) ?>
        <?= Html::hiddenInput('visit', $value["visit"], ['id' => 'visitInput']); ?>
        
        <!-- ปุ่ม submit ส่งข้อมูลไปยัง API actionCheck -->
        <?= Html::submitButton('ปิดสิทธิ์', [
            'class' => 'btn btn-danger', 
            'style' => 'background-color: #00a400; border: 4px solid #dadada; padding: 10px 20px; border-radius: 30px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); font-size: 1.2rem; text-transform: uppercase; cursor: pointer; width: auto;',
        ]); ?>
    <?= Html::endForm(); ?>
</td>
						<td>
    <!-- ปุ่ม GET สำหรับ API actionCheckNhso -->
    <?= Html::a('GET', ['closeall/check-nhso', 'cid' => $value['cid'], 'visit_id' => $value['visit'], 'telephone' => $value['telephone']], [
        'class' => 'btn btn-danger reset-values',
        'style' => 'background-color: #0ba8bd; border: 4px solid #dadada; padding: 10px 20px; border-radius: 30px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); font-size: 1.2rem; text-transform: uppercase; cursor: pointer; width: auto;',
        'onclick' => 'resetValues()' // เรียกใช้งานฟังก์ชัน JavaScript
    ]) ?>
</td>

<script>
    function resetValues() {
        // ล้างค่าของ input fields หรือ hidden input ที่ต้องการ
        document.querySelectorAll('input[type="text"], input[type="hidden"]').forEach(input => input.value = '');
        
        // ถ้ามีฟอร์มที่ต้องการรีเซ็ต ใช้ฟังก์ชัน reset()
        document.querySelectorAll('form').forEach(form => form.reset());

        console.log("ค่ารีเซ็ตเรียบร้อยแล้ว!");
    }
</script>

						
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="13">ไม่พบข้อมูล</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?= Html::endForm(); ?>



<p>
    <?= Html::a('⏪ กลับหน้าหลัก', ['nhso/index3'], [
        'class' => 'btn btn-custom'
    ]) ?>
</p>

<?php
$this->registerCss("
    .btn-custom {
        background: linear-gradient(145deg, #e6e6e6, #ffffff);
        border: 2px solid #ccc;
        border-radius: 12px;
        padding: 10px 20px;
        font-size: 18px;
        font-weight: bold;
        color: #333;
        text-shadow: 1px 1px 1px rgba(255,255,255,0.6);
        box-shadow: 4px 4px 8px rgba(0, 0, 0, 0.2), -4px -4px 8px rgba(255, 255, 255, 0.7);
        transition: all 0.2s ease-in-out;
    }

    .btn-custom:hover {
        background: linear-gradient(145deg, #ffffff, #e6e6e6);
        box-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2), -2px -2px 4px rgba(255, 255, 255, 0.7);
        transform: scale(1.05);
    }

    .btn-custom:active {
        box-shadow: inset 2px 2px 4px rgba(0, 0, 0, 0.2), inset -2px -2px 4px rgba(255, 255, 255, 0.7);
        transform: scale(0.98);
    }
");
?>

<!-- ###################################################################################################### -->
 
<!-- ########################################################################################## -->


<!-- ############################ Setflash Alert 5 วินาที ######################################################### -->
<script>
    // Automatically hide success and error messages after 15 seconds
    setTimeout(function() {
        $('.alert').slideUp('slow');
    }, 15000);
</script>
<!-- ################################################################################################################## -->
<?php

$this->registerJs('
  jQuery("#btn-delete").click(function(){
    var keys = $("#w0").yiiGridView("getSelectedRows");
    console.log(keys);
    if(keys.length>0){
      jQuery.post("' . Url::to(['delete-all']) . '",{ids:keys},function(){
      });
    }
  });
');
?>
<!-- ############################## PASS ################################################################# -->
<div id="model1" style="display: none;">
    <h2 style="color: #2db94d; border: 2px solid #c3e6cb; padding: 5px; text-align: center; border-radius: 10px;">แสดงรายการผ่าน</h2>

    <div class="table-wrapper">
        <?= \yii\grid\GridView::widget([
            'dataProvider' => $passProvider,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'visit_id',
                'pid',
                'users',
                'response',
                'd_update',
            ],
            'tableOptions' => [
                'class' => 'table table-striped table-hover custom-hover', // ใช้คลาสของ Bootstrap และคลาสที่กำหนดเอง
                'style' => 'width: 100%; border: 1px solid #dee2e6; box-shadow: 0px 2px 10px rgba(0,0,0,0.1); border-radius: 10px;', // ใช้ style เพื่อกำหนดเส้นขอบและเงา
            ],
            'headerRowOptions' => ['style' => 'background-color: lightgreen;'],
            'rowOptions' => ['style' => 'background-color: #ecffec;'],
        ]); ?>
    </div>
</div>

<script>
    $(document).ready(function() {
        var tableWrapper = $('.table-wrapper');
        var tableHeight = 200; // กำหนดความสูงของพื้นที่ Scrollbar

        tableWrapper.css({
            'max-height': tableHeight,
            'overflow-y': 'auto',
            'overflow-x': 'hidden'
        });

        // Fix header when scrolling
        var headerClone = tableWrapper.find('thead').clone(); // Clone the table header
        var fixedHeader = $('<div>').addClass('fixed-header'); // Create a fixed header container

        fixedHeader.append(headerClone); // Append the cloned header to fixed container
        fixedHeader.css({
            'position': 'sticky',
            'top': 0,
            'background-color': '#009700', // ให้สีตรงกับสีของส่วนหัว
            'z-index': 1000
        });

        tableWrapper.prepend(fixedHeader); // Add the fixed header to the wrapper
    });
</script>

<!-- ############################## ERROR ################################################################# -->
<div id="model2" style="display: none;">
    <h2 style="color: #ff0000; border: 2px solid #c3e6cb; padding: 5px;">แสดงรายการไม่ผ่าน</h2>

    <?= \yii\grid\GridView::widget([
        'tableOptions' => [
            'class' => 'table table-striped table-hover1',
            'width' => '100%',
            'cellspacing' => '1'
        ],
        'dataProvider' => $errorProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'visit_id',
            'pid',
            'users',
            'response',
            'd_update',
        ],
        'headerRowOptions' => ['style' => 'background-color: #ff5eae; color: white;'],
        'rowOptions' => ['style' => 'background-color: #ffb3b3; color: #ff0000;'],
    ]); ?>
</div>

</div>
</div>


<!-- สคริปต์ jQuery เพื่อแสดง/ซ่อนข้อมูลเมื่อคลิกที่ลิงค์ -->
<?php
$this->registerJs("
    $('#link1').click(function(){
        $('#model1').show();
        $('#model2').hide();
    });

    $('#link2').click(function(){
        $('#model1').hide();
        $('#model2').show();
    });
");
?>
<script>
    $(document).ready(function() {
        $('.popup-link').click(function(e) {
            e.preventDefault();
            var url = $(this).data('url');
            openModalWithData(url);
        });

        $('#selectAll').click(function() {
            // Show the spinner when the button is clicked
            $('#loading-spinner').show();
        });

        // Assuming you have a form with the class 'your-form-class'
        $(document).on('beforeSubmit', 'form[name="frmMain"]', function() {
            // Show the spinner before form submission
            $('#loading-spinner').show();
            return true;
        });

        // If you're using Pjax, hide the spinner on successful Pjax response
        $(document).on('pjax:success', function() {
            $('#loading-spinner').hide();
        });

        // If you're not using Pjax, hide the spinner on any AJAX request completion
        $(document).ajaxStop(function() {
            $('#loading-spinner').hide();
        });
    });

    function openModalWithData(url) {
        $.ajax({
            url: url,
            method: 'GET',
            success: function(response) {
                $('#myModal .modal-body').html(response);
                $('#myModal').modal('show');
            },
            error: function() {
                alert('An error occurred while fetching data.');
            }
        });
    }
</script>