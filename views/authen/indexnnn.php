<?php

namespace app\controllers;

use Yii;
use kartik\grid\GridView;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use yii\helpers\Url;
use kartik\editable\Editable;
use yii\widgets\Pjax;
use yii\grid\ActionColumn;
use yii\grid\CheckboxColumn;
use yii\widgets\LinkPager;




//$this->params['breadcrumbs'][] = ['label' => 'รายงาน', 'url' => ['rep/index']];
//$this->params['breadcrumbs'][] = 'รายงานข้อมูลE-Claim แยกตามREP';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@iconify/iconify@latest/dist/iconify.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <title>Dose1</title>

</head>

<body>
        
   <!-- <script type="text/javascript">
    setTimeout("frmMain.submit();",8000);
    //5000 อยากได้เร็วก็กำหนดตัวเลขน้อยๆเอานะ
    </script> 
-->
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
        #loading-spinner {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            display: none;
        }
    </style>
	
<!-- ########################  ปุ่มเมนู ########################################-->
<style>
   /* เปลี่ยนพื้นหลังเป็นโทนม่วงอ่อน */
body {
    background: linear-gradient(135deg, #e9d5ff, #d8b4fe); /* ไล่เฉดสีม่วงอ่อน */
    font-family: Arial, sans-serif;
}

/* ปรับแต่งกล่องแสดงผล */
.info-box {
    background: linear-gradient(to right, #f3e8ff, #e9d5ff); /* ไล่เฉดสีม่วงอ่อน */
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    padding: 20px;
}

/* ปรับแต่งปุ่มเมนู */
.btn-modern {
    background: linear-gradient(135deg, #c084fc, #a78bfa); /* ไล่เฉดสีม่วง */
}

/* ปรับแต่งหัวข้อ */
.dashboard-title {
    color: #7c3aed; /* สีม่วงเข้ม */
    font-weight: bold;
}

/* ปรับแต่งไอคอน */
.info-box-icon {
    background: linear-gradient(135deg, #a78bfa, #c4b5fd); /* ไล่เฉดสีม่วงอ่อน */
    color: white;
}

</style>
<style>
    .custom-style {
        background: linear-gradient(to right, rgba(221, 214, 243, 0.8), rgba(196, 181, 253, 0.8)); 
        /* Gradient สีม่วงอ่อนแบบโปร่งใส */
        padding: 20px;
        border-radius: 10px;
        backdrop-filter: blur(10px); /* เพิ่มความเบลอให้ดูโปร่งใส */
        position: relative;
    }

    .custom-style::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: url('https://www.transparenttextures.com/patterns/cubes.png'); 
        /* ลวดลายโปร่งใส */
        opacity: 0.2; /* ความโปร่งใสของลวดลาย */
        border-radius: 10px;
    }

    .dashboard-title {
        color: #5a3e8b; /* สีม่วงเข้มสำหรับตัวอักษร */
        font-weight: bold;
        position: relative;
        z-index: 1;
    }
</style>

<!-- ########################  จบปุ่มเมนู ########################################-->
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
    <br>
   
	<div class="text-center custom-style">
    <h1 class="dashboard-title">
        <i class="fas fa-file-invoice-dollar"></i> บริการปิดสิทธิ์การรักษา โรงพยาบาลม่วงสามสิบ
    </h1>
</div>

 <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #f4f4f4;
        }
        .container {
            width: 50%;
            margin: 50px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .btn {
            background-color: #007bff;
            color: white;
            padding: 15px 20px;
            border: none;
            cursor: pointer;
            font-size: 18px;
            border-radius: 5px;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .id-card {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background: #fff;
        }
        img {
            width: 200px;
            border-radius: 5px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
   <div class="container">
    <h2 class="title">โปรดเสียบบัตรประชาชน</h2>
    <button class="btn" onclick="readIDCard()">อ่านบัตร</button>
    <div class="id-card" id="idCardContainer">
        <div class="card-details">
            <img src="idcard_sample.jpg" id="idImage" alt="บัตรประชาชน">
            <div class="info">
                <p><strong>ชื่อ:</strong> <span id="name">-</span></p>
                <p><strong>เลขที่บัตร:</strong> <span id="idNumber">-</span></p>
            </div>
        </div>
    </div>
</div>

<script>
    function readIDCard() {
        document.getElementById('idCardContainer').style.display = 'flex';
        document.getElementById('name').innerText = "นายสมชาย ใจดี";
        document.getElementById('idNumber').innerText = "1234567890123";
    }
</script>

<style>
    .container {
        text-align: center;
        max-width: 380px;
        margin: auto;
        padding: 15px;
        background: #f0f8ff;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    .title {
        font-size: 18px;
        color: #333;
    }
    .btn {
        padding: 8px 20px;
        font-size: 14px;
        background: #007bff;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        margin-top: 10px;
    }
    .id-card {
        display: none;
        background: white;
        padding: 10px;
        border-radius: 8px;
        margin-top: 10px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }
    .card-details {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .info p {
        margin: 3px 0;
        font-size: 14px;
    }
    img#idImage {
        width: 60px;
        border-radius: 5px;
    }
</style>
 <form name="frmMain" method="post" action="<?= Html::encode(['closethaimed/check']); ?>" onsubmit="return checkSelected();">
    <div class="floating-button">
        <button type="submit" name="btnButton1" id="selectAll" class="btn btn-success">
            <i class="fa fa-arrow-circle-right"></i> ส่งข้อมูลปิดสิทธิ์
        </button>
    </div>

    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th><input type="checkbox" id="CheckAll"></th>
                    <th>#</th>
                    <th>วันที่</th>
                    <th>Visit</th>
                    <th>HN</th>
                    <th>Authen</th>
                    <th>CID</th>
                    <th>ชื่อ-สกุล</th>
                    <th>แผนก</th>
                    <th>สิทธิ์</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($visitProvider->getModels() as $value): ?>
                    <tr>
                        <td><input type="checkbox" name="chkDel[]" class="chkItem" value="<?= $value['cid'].$value['visit']; ?>"></td>
                        <td><?= $value["No"]; ?></td>
                        <td class="nowrap"><?= $value["regdate"]; ?></td>
                        <td><?= $value["visit"]; ?></td>
                        <td><?= $value["hn"]; ?></td>
                        <td><?= $value["claimcode"]; ?></td>
                        <td><?= $value["cid"]; ?></td>
                        <td><?= $value["fullname"]; ?></td>
                        <td><?= $value["unit_name"]; ?></td>
                        <td><?= $value["inscl_name"]; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</form>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const checkAll = document.getElementById("CheckAll");
        const checkboxes = document.querySelectorAll(".chkItem");

        checkAll.addEventListener("change", function () {
            checkboxes.forEach(chk => chk.checked = this.checked);
        });

        function checkSelected() {
            const selected = Array.from(checkboxes).some(chk => chk.checked);
            if (!selected) {
                alert("กรุณาเลือกข้อมูลก่อนส่ง");
                return false;
            }
            return true;
        }

        window.checkSelected = checkSelected;
    });
</script>

<style>
    .floating-button {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 1000;
    }

    .floating-button .btn {
        background: #00a400;
        color: white;
        padding: 12px 20px;
        font-size: 16px;
        border: none;
        border-radius: 30px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        cursor: pointer;
        transition: 0.3s;
    }

    .floating-button .btn:hover {
        background: darkgreen;
        transform: scale(1.05);
    }

    .table-container {
        overflow-x: auto;
        max-width: 100%;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    th, td {
        padding: 6px 10px;
        border-bottom: 1px solid #ddd;
        text-align: center;
    }

    th {
        background: #007bff;
        color: white;
    }

    tr:nth-child(even) {
        background: #f9f9f9;
    }

    .nowrap {
        white-space: nowrap;
    }
</style>

<?php
$this->registerCss("
    .btn-custom {
        background: linear-gradient(to bottom, #28a745, #218838);
        border: 2px solid #fff;
        border-radius: 10px;
        color: white;
        font-size: 18px;
        font-weight: bold;
        padding: 10px 20px;
        text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.3);
        box-shadow: 3px 3px 8px rgba(0, 0, 0, 0.3);
        transition: all 0.3s ease-in-out;
    }
    
    .btn-custom:hover {
        background: linear-gradient(to bottom, #218838, #1e7e34);
        box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.5);
        transform: scale(1.05);
    }
");
?>

    <script>
        document.getElementById('api-link').addEventListener('click', function (event) {
            event.preventDefault();
            var apiUrl = this.href;
            var loadingModal = document.getElementById('loading-modal');
            var progressPopup = document.getElementById('progressPopup');
            var progressText = document.getElementById('progressText');
            var currentCidValue = document.getElementById('currentCidValue');

            $('#loading-modal').modal('show');
            progressPopup.style.display = 'block';

            function updateProgressText(message) {
                progressText.innerHTML = message;
            }

            function fetchApiData(apiUrl, cid) {
                return new Promise((resolve, reject) => {
                    $.ajax({
                        url: apiUrl,
                        method: 'GET',
                        success: function (data) {
                            resolve(data);
                        },
                        error: function (error) {
                            reject(error);
                        }
                    });
                });
            }

            async function handleApiCall(apiUrl, cid) {
                try {
                    currentCidValue.innerHTML = cid;
                    updateProgressText(`Fetching data from API for CID: ${cid}...`);
                    const response = await fetchApiData(apiUrl, cid);
                    updateProgressText(`Processing data for CID: ${cid}...`);
                    console.log(response);
                    // Additional data processing if needed
                    updateProgressText(`Data fetched and processed successfully for CID: ${cid}.`);
                } catch (error) {
                    updateProgressText(`Error occurred for CID: ${cid}: ` + error.message);
                } finally {
                    setTimeout(function () {
                        $('#loading-modal').modal('hide');
                        progressPopup.style.display = 'none';
                    }, 2000);
                }
            }

            // Example CID, replace with dynamic CID if available
            var cid = '1234567890123'; 
            handleApiCall(apiUrl, cid);
        });

       
    </script>
</div>

<!-- ############################## PASS ################################################################# -->
<div id="model1" style="display: none;">
    <h2 style="color: #155724; border: 2px solid #c3e6cb; padding: 5px;">แสดงรายการผ่าน</h2>

    <?= \yii\grid\GridView::widget([
        'dataProvider' => $passProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'visit_id',
            'pid',
            'users',
            'response',
            'send_date',
        ],
        'tableOptions' => [
            'class' => 'table table-striped',
            'style' => 'border: 1px solid #dee2e6; box-shadow: 0px 2px 10px rgba(0,0,0,0.1); border-radius: 10px;'
        ],
        'headerRowOptions' => ['style' => 'background-color: #009700;'],
        'rowOptions' => ['style' => 'background-color: lightgreen;'],
    ]); ?>

</div>

<!-- ############################## ERROR ################################################################# -->
<div id="model2" style="display: none;">
<?php 
echo Html::beginForm(['closefdh/delete-multiple'], 'post'); // เริ่มต้นฟอร์ม POST สำหรับ multi-delete
echo \yii\grid\GridView::widget([
    'dataProvider' => $errorProvider,
    'columns' => [
        
        ['class' => 'yii\grid\SerialColumn'], // หมายเลขแถว
        'visit_id',
        'pid',
        'users',
        'response',
        'send_date',
        
        [
            'class' => CheckboxColumn::class,
            'checkboxOptions' => function ($model) {
                return ['value' => $model['id']]; // ใช้ id จาก model เป็นค่า checkbox
            },
        ],
        [
            'class' => ActionColumn::class,
            'template' => '{delete}', // กำหนดปุ่มใน ActionColumn
            'buttons' => [
                'delete' => function ($url, $model, $key) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-trash"></span>',
                        ['delete', 'id' => $model['id']],
                        [
                            'data' => [
                                'confirm' => 'คุณแน่ใจหรือไม่ว่าต้องการลบรายการนี้?',
                                'method' => 'post',
                            ],
                        ]
                    );
                },
            ],
        ],
    ],
    'tableOptions' => [
        'class' => 'table table-striped',
        'style' => 'border: 1px solid #dee2e6; box-shadow: 0px 2px 10px rgba(0,0,0,0.1); border-radius: 10px;',
    ],
    'headerRowOptions' => ['style' => 'background-color: #ff5eae;'],
    'rowOptions' => ['style' => 'background-color: lightred;'],
]);

// ปุ่มสำหรับลบรายการที่เลือก
echo Html::submitButton('ลบรายการที่เลือก', [
    'class' => 'btn btn-danger',
    'data-confirm' => 'คุณแน่ใจหรือไม่ว่าต้องการลบรายการที่เลือก?',
    'data-method' => 'post',
]);

echo Html::endForm(); // จบฟอร์ม POST
?>
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
<!-- ############################################################################################### -->
