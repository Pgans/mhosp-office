<?php

use kartik\grid\GridView;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use yii\helpers\Url;
use yii\data\ArrayDataProvider;
use yii\web\View;
use yii\data\Sort;
use yii\data\ActiveDataProvider;
//use yii\bootstrap4\Alert;
use yii\bootstrap\Modal;




$this->title = 'ข้อมูลการปิดสิทธิ์เฉพาะที่ยังว่าง  ';
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
            background: linear-gradient(to right, #f6c8fa, #a7f3d0); /* Gradient from light green to a bit darker green */
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
					<span class="info-box-text" style="color: green; font-size: 18px;">ยอดบริการ <?php echo "ทั้งหมด:$todayopd "; ?>ราย</span>
					<span class="info-box-number">
						<?php
echo "<a>ขอAuthen:</a> <span style='color:green;'>$authen</span> | ";
echo "<a href='index.php?r=authen/index' style='color:orange;'>เหลือ: $noauthen</a>";
?>

					<div>
					<?php echo "<a>ต่างด้าว:</a> <span style='color:green;'>$alien</span>"; ?> |
					<!--<?php echo "<a>hw:</a><span style='color:green;'>$homeward</span>"; ?>-->
			        <?php echo "<a>admit:</a><span style='color:green;'>$todayipd</span>"; ?>
					 <?php echo "<a>hd:</a><span style='color:green;'>$hd</span>"; ?>
					</div>
					</div>
			                  
            </div>
            <!-- /.info-box -->
        </div>

        <div class="col-xl-3 col-md-3 mb-3">
            <div class="info-card bg-warning" style="box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.3);">
                <span class="info-box-icon"><i class="fa-sharp fa-solid fa-compass" style="color: red;"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text" style="color: red; font-size: 18px;">ข้อมูลปิดสิทธิ์</span>
                    <span class="info-box-number">
						<?php
						echo "<a href='index.php?r=closeall3/index'>ปิดสิทธิ์:</a> <span style='color:green;'>$closevisits</span> | ";
						echo "<a href='index.php?r=closeall2/index' style='color:orange;'>เหลือ: $noclosevisit</a>";
						?>
				
					</span>
                </div>
               <div style="text-align: right;">
                <a href="<?= Url::to(['closeall2/run-curl']) ?>" class="btn btn-info" style="font-size: 16px;">
                                    รันToken-FDHปิดสิทธิ์ <i class="fa fa-arrow-circle-right"></i>
                                </a>
						

                   <!-- <?= Html::a('<i class="fa fa-ban" aria-hidden="true"></i> ไม่ผ่าน', null, ['class' => 'btn btn-danger', 'id' => 'link2']) ?>-->
				   <!--
                     <?=  Html::a(
    '<i class="fa fa-trash" aria-hidden="true"></i> ลบToken', // ชื่อปุ่ม
    ['delete-specific1'],       // เส้นทางไปยัง actionDeleteSpecific
    [
        'class' => 'btn btn-lightdanger', // เพิ่มสไตล์ปุ่ม
        'data' => [
            'confirm' => 'คุณแน่ใจหรือไม่ว่าต้องการลบ 10 รายการที่ไม่สำเร็จ?', // การยืนยันก่อนลบ
            'method' => 'post', // ใช้ POST เพื่อความปลอดภัย
        ],
    ]
); ?>
-->
                </div>
                </a>
            </div>
        </div>


        <div class="col-xl-3 col-md-3 mb-3">
            <div class="info-card bg-warning" style="box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.3);">
                <span class="info-box-icon"><i class="fas fa-hand-holding-heart" style="color: orange;"></i></i></span>
                <div class="info-box-content">

                    <span class="info-box-number" style="color: green; font-size: 18px;">mBase</span>
						<?php
						echo "<strong><a>จองเคลม:</a></strong> <span style='color:green; font-weight:bold;'>$jongclaim</span> | <strong><a <a href='index.php?r=closevisit1/index'>เหลือ:</a></strong> <span style='color:orange; font-weight:bold;'>$nojongclaim</span>";
						?>
						<br>
						<?php
						echo "<strong><a>Totalvisit:</a></strong> <span style='color:green; font-weight:bold;'>" . (isset($totalvisit) ? $totalvisit : 0) . "</span> | ";
						echo "<strong><a>เหลือ:</a></strong> <span style='color:orange; font-weight:bold;'>" . (isset($nototalvisit) ? $nototalvisit : 0) . "</span>";
						?>
						<br>
						<?php
						echo "<strong><a>phr:</a></strong> <span style='color:green; font-weight:bold;'>" . (isset($phr) ? $phr : 0) . "</span> | ";
						echo "<strong><a>เหลือ:</a></strong> <span style='color:orange; font-weight:bold;'>" . (isset($nophr) ? $nophr : 0) . "</span>";
						?>
					</span>
                    </div>
                    
                    
                    <!-- ลิงก์ที่เรียกใช้ JavaScript เพื่อเปิดป๊อปอัป -->
                    <!-- <div class="text-center">
                            <a href="javascript:void(0);" id="exportExcelButton" class="btn btn-primary" style="font-size: 16px; border: 4px solid #91ffff;" onclick="openPopup();">
                                รายชื่อและส่งออก ExcelFiles <i class="fa fa-arrow-circle-right"></i>
                            </a>
                        </div> -->

                    <script>
                        function openPopup() {
                            const url = "<?= \yii\helpers\Url::to(['closeall2/exportexcel']); ?>"; // ลิงก์ไปยังไฟล์ Excel
                            const popupWindow = window.open(url, 'ExcelPopup', 'width=600,height=400'); // สร้างหน้าต่างป๊อปอัป
                            popupWindow.focus(); // โฟกัสไปยังป๊อปอัป
                        }
                    </script>


                  
                      
  
                    
                </div>
            </div>
  
        <div class="col-xl-3 col-md-3 mb-3">
            <div class="info-card bg-warning" style="box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.3);">
                <span class="info-box-icon"><i class="fas fa-hand-holding-heart" style="color: orange;"></i></i></span>
                <div class="info-box-content">

							<span class="info-box-number" style="color: green; font-size: 18px;">
			JHCIS <?php echo "ทั้งหมด: " . (isset($visitj) ? $visitj : 0); ?> ราย
		</span>

		<?php
		echo "<strong><a>จองเคลม:</a></strong> <span style='color:green; font-weight:bold;'>" . (isset($jongclaimj) ? $jongclaimj : 0) . "</span> | ";
		echo "<strong><a>เหลือ:</a></strong> <span style='color:orange; font-weight:bold;'>" . (isset($nojongclaimj) ? $nojongclaimj : 0) . "</span>";
		?>
		<br>
		<?php
		echo "<strong><a>authen:</a></strong> <span style='color:green; font-weight:bold;'>" . (isset($authenj) ? $authenj : 0) . "</span> | ";
		echo "<strong><a>เหลือ:</a></strong> <span style='color:orange; font-weight:bold;'>" . (isset($noauthenj) ? $noauthenj : 0) . "</span>";
		?>
		<br>
		<?php
		echo "<strong><a>ปิดสิทธิ์:</a></strong> <span style='color:green; font-weight:bold;'>" . (isset($closevisitj) ? $closevisitj : 0) . "</span> | ";
		echo "<strong><a>เหลือ:</a></strong> <span style='color:orange; font-weight:bold;'>" . (isset($noclosevisitj) ? $noclosevisitj : 0) . "</span>";
		?>  
                </div>
            </div>
        </div>
    </div>
 
	
<div class="card">
    <div class="card-header bg-primary text-white">
       
    </div>
   
<div class="card-body" style="display: flex; align-items: center; gap: 20px; flex-wrap: wrap;">

    <!-- ปุ่มอ่านบัตร -->
    <button id="read-card" class="btn btn-success" 
        style="background-color: #0099FF; border: 4px solid #dadada; padding: 10px 20px; border-radius: 30px; 
               box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); font-size: 2.2rem; text-transform: uppercase; cursor: pointer; width: auto;">
        📟 API-FDH
    </button>

    <!-- ปุ่มกลับหน้าหลัก สไตล์ Canva -->
<?= Html::a('⏪ กลับหน้าหลัก', ['closeall/index'], [
    'class' => 'btn btn-custom',
    'style' => '
        font-size: 1.5rem;
        padding: 12px 28px;
        border-radius: 30px;
        background: linear-gradient(135deg, #c4f5e1, #fbc2eb);
        color: #fff;
        font-weight: 600;
        border: none;
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
    ',
    'onmouseover' => "this.style.background='linear-gradient(135deg, #fbc2eb, #a18cd1)'; this.style.transform='scale(1.05)';",
    'onmouseout'  => "this.style.background='linear-gradient(135deg, #a18cd1, #fbc2eb)'; this.style.transform='scale(1)';"
]) ?>

    <!-- ข้อความขอ Authen -->
    <div style="font-size: 2.2rem; font-weight: bold; color: deeppink;">
       ขอAuthen-FDH ปิดสิทธิ์ที่ยังว่าง 
    </div>

    <!-- นาฬิกา -->
    <div style="font-size: 2.2rem; font-weight: bold; color: deeppink;">
        ⏰ <span id="current-time">--:--:--</span>
    </div>

    <!-- ชื่อผู้ใช้อยู่ด้านขวาสุด -->
    <div style="margin-left: auto; font-size: 1.8rem; font-weight: bold; color: #0099a4;">
        👤 
        <?php
        if (!Yii::$app->user->isGuest) {
            echo Yii::$app->user->identity->username ;
        } else {
            echo 'Guest';
        }
        ?>
    </div>
</div>

<!-- สำหรับแสดงผลหลังอ่านบัตร -->
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
$url = Url::to(['closeall2/read-smart-card']);
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
    
<!-- <?= Html::beginForm(['closeall2/check'], 'post', ['name' => 'frmMain', 'id' => 'frmMain']); ?>-->
<?php
// ฟังก์ชัน JS ที่ scroll ไปยัง visit ที่ถูกเน้น
if (Yii::$app->request->get('highlight')) {
    $highlightVisit = Yii::$app->request->get('highlight');
    $js = <<<JS
document.addEventListener("DOMContentLoaded", function() {
    const el = document.getElementById("visit{$highlightVisit}");
    if (el) {
        el.scrollIntoView({ behavior: "smooth", block: "center" });
        el.style.backgroundColor = "#ffef99";
        setTimeout(function() {
            el.style.backgroundColor = "";
        }, 2000);
    }
});
JS;
    $this->registerJs($js);
}
?>

<div style="overflow: auto; height: 600px; border: 1px solid #ddd;">
    <table class="table my-striped-table" width="1000" border="0" bordercolor="#ddd" 
           style="border-collapse: collapse; box-shadow: 2px 2px 5px rgba(0,0,0,0.2);">
        <thead style="position: sticky; top: 0; background-color: #fff; z-index: 1;">
            <tr>
                <th>#</th>
                <th>วันที่</th>
                <th>Visit</th>
                <th><?= $visitProvider->sort->link('cid') ?></th>
                <th><?= $visitProvider->sort->link('claimcode') ?></th>
                <th><?= $visitProvider->sort->link('enpoint') ?></th>
                <th>ค่ารักษา</th>
                <th>Hn</th>
                <th>ชื่อ-สกุล</th>
                <th>อายุ</th>
                <th>แผนกลงทะเบียน</th>
                <th>แผนกสิ้นสุด</th>
                <th>รหัสโรค</th>
                <th>เบอร์โทร</th>
                <th>สิทธิ์</th>
                <th>authen</th>
                <th>ปิดสิทธิ์</th>
                <th>ดึงข้อมูล</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($visitProvider && $visitProvider->getCount() > 0): ?>

            <?php
            // ✅ ดึงข้อมูลทั้งหมดออกมาเป็น array
            $models = $visitProvider->getModels();

            // ✅ เตรียมเก็บจำนวน cid ซ้ำ
            $cids = array_column($models, 'cid');
            $cidCounts = array_count_values($cids);
            ?>

            <?php foreach ($models as $key => $value) : ?>
                <tr data-visit="<?= $value["visit"] ?>">
                    <td><?= $value["No"]; ?></td>
                     <td style="color: <?= $value["enpoint"] === '' ? 'red' : 'black' ?>;">
						<?= $value["regdate"]; ?>
						</td>
                    <td><?= $value["visit"]; ?></td>

                    <!-- ✅ เช็ค cid ซ้ำ -->
                    <td style="<?= ($cidCounts[$value['cid']] > 1) 
                        ? 'background-color:#d8b4fe; color:#4b0082; font-weight:bold;' 
                        : '' ?>">
                        <?= $value["cid"]; ?>
                    </td>

                    <td style="color: <?= $value['status'] === '200' ? 'green' : 'orange'; ?>">
							<?= $value["claimcode"]; ?>
						</td>
                    <td style="color: green;"><?= $value["enpoint"]; ?></td>

                    <?php
                    $amount = $value["amount"];
                    $color = ($amount == 0.00) ? "red" : "green";
                    ?>
                    <td style="color: <?= $color ?>;"><?= htmlspecialchars($amount) ?></td>

                    <td><?= $value["hn"]; ?></td>
                    <td><?= $value["fullname"]; ?></td>
                    <td><?= $value["age"]; ?></td>
                    <td><?= $value["unit_name"]; ?></td>

					<td style="color: <?= $value['units'] === 'สิ้นสุดบริการ' ? 'green' : 'red'; ?>;">
						<?= Html::encode($value['units']) ?>
					</td>


                    <td><?= $value["icd10_tm"]; ?></td>

                    <?php
                    $phone = $value["telephone"];
                    $phoneColor = (strlen($phone) == 10) ? "green" : "red";
                    ?>
                    <td style="color: <?= $phoneColor ?>;"><?= htmlspecialchars($phone) ?></td>

                    <td><?= $value["inscl_name"]; ?></td>

                    <!-- ✅ ปุ่ม authen -->
                    <td>
                        <?= Html::beginForm(['closeall2/check1'], 'post', [
                            'id' => 'fdhForm1_' . $key,
                            'class' => 'fdhForm1'
                        ]) ?>
                            <?= Html::hiddenInput('visit', $value["visit"], ['class' => 'visitInput1']); ?>
                            <?= Html::submitButton('authen', [
                                'class' => 'btn btn-danger authen-btn',
                                'style' => 'background-color: #ab29c2; border: 4px solid #dadada; padding: 10px 20px; border-radius: 30px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); font-size: 1.2rem; text-transform: uppercase; cursor: pointer;',
                            ]) ?>
                        <?= Html::endForm(); ?>
                    </td>

                    <!-- ✅ ปุ่มปิดสิทธิ์ -->
                    <td>
                        <?= Html::beginForm(['closeall2/check'], 'post', [
                            'id' => 'fdhForm_' . $key,
                            'class' => 'fdhForm'
                        ]) ?>
                            <?= Html::hiddenInput('visit', $value["visit"], ['class' => 'visitInput']); ?>
                            <?= Html::submitButton('ปิดสิทธิ์', [
                                'class' => 'btn btn-danger close-btn',
                                'style' => 'background-color: #00a400; border: 4px solid #dadada; padding: 10px 20px; border-radius: 30px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); font-size: 1.2rem; text-transform: uppercase; cursor: pointer; width: auto;',
                            ]) ?>
                        <?= Html::endForm(); ?>
                    </td>

                    <!-- ✅ ปุ่ม GET -->
                    <td>
                        <?= Html::a('GET', [
                            'closeall2/check-nhso',
                            'cid' => $value['cid'],
                            'visit_id' => $value['visit'],
                            'telephone' => $value['telephone'],
                            'action' => 'get'
                        ], [
                            'class' => 'btn btn-danger get-btn',
                            'style' => 'background-color: #0ba8bd; border: 4px solid #dadada; padding: 10px 20px; border-radius: 30px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); font-size: 1.2rem; text-transform: uppercase; cursor: pointer; width: auto;',
                        ]) ?>
                    </td>
				<script type="text/javascript">
    // เมื่อกดปุ่ม submit ให้ล้างค่าของ hidden input ก่อนส่งฟอร์ม
   document.getElementById('fdhForm').addEventListener('submit', function() {
    setTimeout(function() {
        document.getElementById('visitInput').value = '';
    }, 100); // ล้างหลัง submit เล็กน้อย
});
</script>	
                </tr>
				
            <?php endforeach; ?>

        <?php else: ?>
            <tr><td colspan="18">ไม่พบข้อมูล</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>


<script>
document.addEventListener("DOMContentLoaded", function() {
    const container = document.querySelector('div[style*="overflow: auto"]');
    const params = new URLSearchParams(window.location.search);

    const visitIdFromUrl = params.get('visit_id') || params.get('visit');
    const actionFromUrl = params.get('action');

    function scrollToVisit(visit) {
        if (!visit) return;
        const targetRow = document.querySelector('tr[data-visit="' + visit + '"]');
        if (targetRow && container) {
            container.scrollTop = targetRow.offsetTop - container.offsetTop;
            targetRow.style.backgroundColor = '#ffff99';
            setTimeout(() => {
                targetRow.style.backgroundColor = '';
            }, 3000);
        } else if (targetRow) {
            targetRow.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }

    if (visitIdFromUrl && actionFromUrl) {
        scrollToVisit(visitIdFromUrl);
        sessionStorage.setItem('lastVisit', visitIdFromUrl);
        sessionStorage.setItem('lastAction', actionFromUrl);
    } else {
        const lastVisit = sessionStorage.getItem('lastVisit');
        const lastAction = sessionStorage.getItem('lastAction');
        if (lastVisit && lastAction) {
            scrollToVisit(lastVisit);
        }
    }

    // ปิดสิทธิ์
    document.querySelectorAll('.fdhForm').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const visitVal = this.querySelector('.visitInput').value;
            fetch(this.action, {
                method: this.method,
                body: new FormData(this),
            })
            .then(response => response.text())
            .then(data => {
                const url = new URL(window.location.href);
                url.searchParams.set('visit_id', visitVal);
                url.searchParams.set('action', 'close');
                window.location.href = url.toString();
            })
            .catch(error => {
                alert('เกิดข้อผิดพลาดในการส่งข้อมูล');
                console.error(error);
            });
        });
    });

    // GET
    document.querySelectorAll('.get-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const href = this.getAttribute('href');
            const url = new URL(href, window.location.origin);
            sessionStorage.setItem('lastVisit', url.searchParams.get('visit_id'));
            sessionStorage.setItem('lastAction', url.searchParams.get('action'));
            window.location.href = href;
        });
    });

    // authen
    document.querySelectorAll('.fdhForm1').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const visitVal = this.querySelector('.visitInput1').value;
            fetch(this.action, {
                method: this.method,
                body: new FormData(this),
            })
            .then(response => response.text())
            .then(data => {
                const url = new URL(window.location.href);
                url.searchParams.set('visit_id', visitVal);
                url.searchParams.set('action', 'authen');
                window.location.href = url.toString();
            })
            .catch(error => {
                alert('เกิดข้อผิดพลาดในการส่งข้อมูล');
                console.error(error);
            });
        });
    });
});

</script>

<div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 20px; flex-wrap: wrap;">
    <!-- กลับหน้าหลัก -->
    <div>
        <?= Html::a('⏪ กลับหน้าหลัก', ['closeall/index'], [
            'class' => 'btn btn-custom',
            'style' => 'font-size: 1.2rem;'
        ]) ?>
    </div>
</p><!-- ขั้นตอนการใช้งาน -->
    <div style="
        font-size: 1.5rem;
        color: #333;
        background-color: #ffffff;
        padding: 15px 20px;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        flex: 1;
        min-width: 260px;
    ">
       <div style="
  background: linear-gradient(135deg, #f3e8ff, #e0f7fa);
  border-radius: 20px;
  padding: 25px 30px;
  box-shadow: 0 4px 15px rgba(0,0,0,0.1);
  font-family: 'Prompt', sans-serif;
  color: #333;
  max-width: 800px;
  margin: 30px auto;
">

  <h4 style="
    margin: 0 0 20px;
    color: #6f42c1;
    font-size: 22px;
    font-weight: 600;
    text-align: center;
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 8px;
  ">
    📝 ขั้นตอนการใช้งาน
  </h4>

  <ol style="
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 20px;
    padding: 0;
    margin: 0;
    list-style: none;
  ">

    <li style="background: #ffffff; border-radius: 14px; padding: 12px 18px; width: 45%; text-align: center; color: #4a148c; font-weight: 500; box-shadow: 0 2px 6px rgba(0,0,0,0.1);">
      1️⃣ เสียบบัตรประชาชนเข้ากับเครื่องอ่าน
    </li>

    <li style="background: #f3e5f5; border-radius: 14px; padding: 12px 18px; width: 45%; text-align: center; color: #6a1b9a; font-weight: 500; box-shadow: 0 2px 6px rgba(0,0,0,0.1);">
      2️⃣ กดปุ่ม "📟 อ่านบัตรประจำตัวประชาชนสำหรับเจ้าหน้าที่"
    </li>

    <li style="background: #e8f5e9; border-radius: 14px; padding: 12px 18px; width: 45%; text-align: center; color: #1b5e20; font-weight: 500; box-shadow: 0 2px 6px rgba(0,0,0,0.1);">
      3️⃣ กดปุ่มรัน Token-Pgans
    </li>

    <li style="background: #fffde7; border-radius: 14px; padding: 12px 18px; width: 45%; text-align: center; color: #f57f17; font-weight: 500; box-shadow: 0 2px 6px rgba(0,0,0,0.1);">
      4️⃣ กดปุ่ม GET เพื่อตรวจสอบสิทธิ์
    </li>

    <li style="background: #e3f2fd; border-radius: 14px; padding: 12px 18px; width: 45%; text-align: center; color: #0d47a1; font-weight: 500; box-shadow: 0 2px 6px rgba(0,0,0,0.1);">
      5️⃣ กดปุ่มปิดสิทธิ์เมื่อยังไม่เคยขอ
    </li>

  </ol>
</div>


