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



$this->title = 'ข้อมูลการขอ AutenCode';
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
   
   
   
	
<div class="card">
    <div class="card-header bg-primary text-white">
       
    </div>
    <div class="card-body">
    <div class="card-body" style="display: flex; align-items: center; gap: 20px;">
    <button id="read-card" class="btn btn-success" 
        style="background-color: #05b359; border: 4px solid #dadada; padding: 10px 20px; border-radius: 30px; 
               box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); font-size: 2.2rem; text-transform: uppercase; cursor: pointer; width: auto;">
        📟 อ่านบัตรประจำตัวประชาชนสำหรับเจ้าหน้าที่
    </button>

    <div style="font-size: 2.2rem; font-weight: bold; color: deeppink;">
        ตรวจสอบขอAuthenCode รายที่ว่าง...
    </div>

    <div style="font-size: 2.2rem; font-weight: bold; color: deeppink;">
        ⏰ <span id="current-time">--:--:--</span>
    </div>
</div>

<div id="result" class="mt-3"></div>

<?php
$script = <<< JS
function updateTime() {
    var now = new Date();
    var hours = String(now.getHours()).padStart(2, '0');
    var minutes = String(now.getMinutes()).padStart(2, '0');
    var seconds = String(now.getSeconds()).padStart(2, '0');
    var timeString = hours + ':' + minutes + ':' + seconds;
    document.getElementById('current-time').textContent = timeString;
}
setInterval(updateTime, 1000);
updateTime();
JS;
$this->registerJs($script);
?>


<?php
$url = Url::to(['closephysical/read-smart-card']);
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
    <div class="page-container">
        <div class="fixed-header">
            <!-- <input name="btnButton1" class="btn btn-success btn btn-block" id="selectAll" type="submit" name="selectAll" value="ส่งข้อมูล FDH OPD" style="background-color: #007100; border: 4px solid #dadada;"> -->
        </div>
       
            
<?= Html::beginForm(['authen/check'], 'post', ['name' => 'frmMain', 'id' => 'frmMain']); ?>

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
                <th>สิทธิ์</th>
                <th>ค่ารักษา</th>
                <th>ขอ authen</th>
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
                        <td style="color: orange;"><?= $value["claimKiosk"]; ?></td>
                        <td style="color: green;"><?= $value["enpoint"]; ?></td>
                        <td><?= $value["hn"]; ?></td>
                        <td><?= $value["fullname"]; ?></td>
                        <td><?= $value["age"]; ?></td>
                        <td><?= $value["unit_name"]; ?></td>
                        <td><?= $value["inscl_name"]; ?></td>
                        <td><?= $value["amount"]; ?></td>
						 <td>
                    <?= Html::beginForm(['authen/check'], 'post', ['id' => 'fdhForm1_' . $key, 'class' => 'fdhForm1']) ?>
                        <?= Html::hiddenInput('visit', $value["visit"], ['class' => 'visitInput1']); ?>
                        <?= Html::submitButton('authen', [
                            'class' => 'btn btn-danger authen-btn', 
                            'style' => 'background-color: #ab29c2; border: 4px solid #dadada; padding: 10px 20px; border-radius: 30px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); font-size: 1.2rem; text-transform: uppercase; cursor: pointer;',
                        ]) ?>
                    <?= Html::endForm(); ?>
                </td>
<td>
    <!-- ปุ่ม GET สำหรับ API actionCheckNhso -->
    <?= Html::a('GET', ['authen/check-nhso', 'cid' => $value['cid'], 'visit_id' => $value['visit'], 'telephone' => $value['telephone']], [
        'class' => 'btn btn-danger',
        'style' => 'background-color: #0ba8bd; border: 4px solid #dadada; padding: 10px 20px; border-radius: 30px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); font-size: 1.2rem; text-transform: uppercase; cursor: pointer; width: auto;',
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
<script type="text/javascript">
    // เมื่อกดปุ่ม submit ให้ล้างค่าของ hidden input ก่อนส่งฟอร์ม
    document.getElementById('fdhForm').onsubmit = function() {
        // ล้างค่า visit ใน hidden input
        document.getElementById('visitInput').value = '';
    };
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
 <script>
    function ClickCheckAll(vol) {
        var i;
        for (i = 0; i < document.frmMain.elements.length; i++) {
            if (document.frmMain.elements[i].name == "chkDel[]") {
                document.frmMain.elements[i].checked = vol.checked;
            }
        }
    }

    // Function to handle form submission with row processing
    document.querySelector('form[name="frmMain"]').addEventListener('submit', function(event) {
        var rows = document.querySelectorAll('table.table-striped tr');
        var checkedRows = document.querySelectorAll('input[name="chkDel[]"]:checked');
        var count = checkedRows.length;
        var scrollContainer = document.getElementById('scrollContainer');

        if (count > 0) {
            var currentIndex = 0;

            function processRow() {
                if (currentIndex < count) {
                    var row = checkedRows[currentIndex].closest('tr');
                    var originalBackgroundColor = row.style.backgroundColor;
                    row.style.backgroundColor = '#F8B6F6'; // Set processing background color

                    // Scroll the row into view
                    row.scrollIntoView({ behavior: 'smooth', block: 'center', inline: 'nearest' });

                    // Simulate API call or other processing
                    setTimeout(function() {
                        // Example: Update row style back to original color
                        row.style.backgroundColor = originalBackgroundColor;

                        // Move to the next row
                        currentIndex++;
                        processRow();
                    }, 1000); // Simulate delay for demonstration
                } else {
                    // Finish processing
                  // alert('API ตรวจสอบข้อมูลเรียบร้อย.');

                    // Submit the form
                    document.frmMain.submit();
                }
            }

            // Start processing rows
            processRow();
        } else {
            // No rows selected
          //  alert('Please select rows to process.');
        }

        // Prevent form submission
        event.preventDefault();
    });
</script>

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