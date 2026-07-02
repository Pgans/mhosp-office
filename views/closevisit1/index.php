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




$this->title = 'จองเคลม FDH';
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
<style>
    .info-box-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 90px; /* ปรับขนาดให้เหมาะสม */
        height: 90px; /* ปรับขนาดให้เหมาะสม */
        background: linear-gradient(135deg, #d4e157, #aeea00); /* Gradient สีเขียวอ่อน */
        border-radius: 50%;
        color: white;
    }
    .info-box-icon i {
        font-size: 24px; /* ปรับขนาดไอคอน */
    }
</style>
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
    .btn-group-modern {
        display: flex;
        gap: 10px;
        justify-content: center;
        margin-top: 10px;
    }
    
    .btn-modern {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 12px 20px;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        font-weight: bold;
        text-transform: uppercase;
        text-decoration: none;
        color: white;
        transition: all 0.3s ease-in-out;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
    }

    .btn-modern i {
        font-size: 18px;
    }

    /* ปรับสีปุ่ม */
    .btn-cidhn {
        background: linear-gradient(135deg, #d4e157, #aeea00);
    }

    .btn-opd {
        background: linear-gradient(135deg, #4facfe, #00f2fe);
    }

    .btn-ipd {
        background: linear-gradient(135deg, #ff512f, #dd2476);
    }
	.btn-refers {
        background: linear-gradient(135deg, #18abab, #35e8d7
	);
	
	
    }
    /* Hover Effect */
    .btn-modern:hover {
        transform: translateY(-3px);
        box-shadow: 0px 6px 15px rgba(0, 0, 0, 0.3);
    }
	@media (max-width: 1024px) {
    .table {
        font-size: 13px;
    }
}
CSS
);
</style>
<div class="btn-group-modern">
	 <a href="<?= Url::to(['/computer/authen']) ?>" class="btn-modern btn-opd">
        <i class="fa fa-check-square-o"></i>Dashboard
    </a>
    <a href="<?= Url::to(['/closeall/index']) ?>" class="btn-modern btn-opd">
        <i class="fa fa-check-square-o"></i>ปิดสิทธิ์ mBase
    </a>

    <a href="<?= Url::to(['/closevisit1/index']) ?>" class="btn-modern btn-ipd">
        <i class="fa fa-check-square-o"></i> จองเคลม FDH
    </a>
	<a href="<?= Url::to(['/logclosevisits/index']) ?>" class="btn-modern btn-refers">
        <i class="fa fa-check-square-o"></i> mBase-ตรวจจองเคลม
    </a>
	 <a href="<?= Url::to(['/closevisitjhcis/index']) ?>" class="btn-modern btn-refers">
        <i class="fa fa-check-square-o"></i> Jhcis-จองเคลม
    </a>
	 <a href="<?= Url::to(['/logclosevisitsj/index']) ?>" class="btn-modern btn-refers">
        <i class="fa fa-check-square-o"></i> Jhcis-ตรวจจองเคลม
    </a>
</div>
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
    <div class="well">
	
    <div class="col-xl-3 col-md-3 mb-3" >
            <div class="info-box" style="
    background: linear-gradient(to right, #d9f99d, #a7f3d0); /* Gradient from light green to a bit darker green */
    padding: 20px; /* Optional: adjust padding as needed */
    border-radius: 8px; /* Optional: adjust border radius for rounded corners */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Optional: add shadow for better appearance */
    color: #333; /* Optional: text color */
	">
                <span class="info-box-icon"><i class="far fa-calendar-check"style="color: green;"></i></span>
                <div class="info-box-content">
                <span class="info-box-text" style="color: green; font-size: 18px;">ผ่านตามเงื่อนไขวันนี้</span>
                    <span class="info-box-number"><?php echo $amount ?></span>
                </div>
                <!-- /.info-box-content -->
                <!-- <a href="<?= \yii\helpers\Url::to(['/log/dt']) ?>" target="_blank" class="info-box-more"> -->
                    <div style="text-align: right;">
                        <div style="text-align: right;">
                        <?= Html::a('<i class="fa fa-check-square" aria-hidden="true"></i> ผ่าน', null, ['class' => 'btn btn-lightgreen', 'id' => 'link1']) ?>
                            <img src="images/accept.svg" title="ข้อมูลสำเร็จ" width="10" height="10">
                        </div>
                    </div>
                </a>
            </div>
            <!-- /.info-box -->
        </div>

        <div class="col-xl-3 col-md-3 mb-3">
           <div class="info-box" style="
    background: linear-gradient(to right, #d9f99d, #a7f3d0); /* Gradient from light green to a bit darker green */
    padding: 20px; /* Optional: adjust padding as needed */
    border-radius: 8px; /* Optional: adjust border radius for rounded corners */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Optional: add shadow for better appearance */
    color: #333; /* Optional: text color */
	">
            <span class="info-box-icon" ><i class="fa-sharp fa-solid fa-compass"style="color: red;"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text" style="color: red; font-size: 18px;">ไม่ผ่านตามเงื่อนไข</span>
                    <span class="info-box-number"><?php echo $amountx ?></span>
                    
                </div>
               
                    <div style="text-align: right;">
                    <?= Html::a('<i class="fa fa-ban" aria-hidden="true"></i> ไม่ผ่าน', null, ['class' => 'btn btn-lightdanger', 'id' => 'link2']) ?>
                    <?=  Html::a(
    '<i class="fa fa-trash" aria-hidden="true"></i> ลบ', // ชื่อปุ่ม
    ['delete-specific'],       // เส้นทางไปยัง actionDeleteSpecific
    [
        'class' => 'btn btn-lightdanger', // เพิ่มสไตล์ปุ่ม
        'data' => [
            'confirm' => 'คุณแน่ใจหรือไม่ว่าต้องการลบ 10 รายการที่ไม่สำเร็จ?', // การยืนยันก่อนลบ
            'method' => 'post', // ใช้ POST เพื่อความปลอดภัย
        ],
    ]
); ?>
                        <img src="images/reject.76840914.svg" title="ไม่ผ่าน" width="20" height="10">
                    </div>
                </a>
            </div>
        </div>
		

        <div class="col-xl-3 col-md-3 mb-3">
           <div class="info-box" style="
    background: linear-gradient(to right, #d9f99d, #a7f3d0); /* Gradient from light green to a bit darker green */
    padding: 20px; /* Optional: adjust padding as needed */
    border-radius: 8px; /* Optional: adjust border radius for rounded corners */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Optional: add shadow for better appearance */
    color: #333; /* Optional: text color */
	">
                <span class="info-box-icon"><i class="fas fa-hand-holding-heart" style="color: orange;"></i></i></span>
                <div class="info-box-content">
                    <span class="info-box-text" style="color: green; font-size: 18px;">รายการส่งผ่านทั้งหมด</span>
                    <span class="info-box-number"><?php echo $total ?></span>
                </div>
				 <div style="text-align: right;">
                
				</div>
				
                <div style="text-align: right;">
                <a href="<?= Url::to(['closevisit1/run-curl']) ?>" class="btn btn-lightgreen" style="font-size: 14px;">
                                    RunToken <i class="fa fa-arrow-circle-right"></i>
                                </a>
                <img src="images/waitmoph.98529de4.svg" title="ยอดทั้งหมด" width="10" height="10">
                    </div>
            </div>
            <!-- /.info-box -->
        </div>
       <div class="col-xl-3 col-md-3 mb-3">
            <div class="info-box" style="
    background: linear-gradient(to right, #d9f99d, #a7f3d0); /* Gradient from light green to a bit darker green */
    padding: 20px; /* Optional: adjust padding as needed */
    border-radius: 8px; /* Optional: adjust border radius for rounded corners */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Optional: add shadow for better appearance */
    color: #333; /* Optional: text color */
	">
              <div class="info-box-content">
            <span class="info-box-text" style="color: green; font-size: 18px;">ยอดบริการวันนี้</span>
            <span class="info-box-number">
                <?php echo "UCS:$todayx | ทั้งหมด:$todayipd "; ?>
            </span>
            <div>
            <?php echo "ต่างด้าว: $alien"; ?> </br>
			<?php echo "homeward: $homeward"; ?>
            </div>
            </div>
        </div>
       
			
            <!-- /.info-box -->
        </div>

        <div id="loading-spinner" style="display: none; color: purple; font-size: 100px;">
            <!-- Customize the style as needed -->
            <div class="custom-spinner"></div>
        </div>

        <div id="loading-spinner" style="display: none; color: purple; font-size: 100px;">
            <!-- Customize the style as needed -->
            <div class="custom-spinner"></div>
        </div>


<?= Html::beginForm(['closevisit1/check'], 'post', ['name' => 'frmMain']); ?>

<input name="btnButton1" class="btn btn-success btn btn-block" id="selectAll" type="submit" value="ส่งข้อมูล FDH Close Visits" style="background-color: #0fab42; border: 4px solid #dadada;">

<style>
    .table-striped-custom tbody tr:nth-child(even) {
        background-color: #eaffea;
    }
    .table-striped-custom tbody tr:nth-child(odd) {
        background-color: white;
    }
    .table-striped-custom tbody tr:hover {
        background-color: #c1f0c1;
    }
</style>

<table class="table table-striped-custom" width="100%" border="0">
    <thead>
        <tr>
            <th style="background-color: lightgray;"><input name="CheckAll" type="checkbox" id="CheckAll" value="Y" onClick="ClickCheckAll(this);"></th>
            <th style="background-color: lightgray;">#</th>
            <th style="background-color: lightgray;">วันที่</th>
            <th style="background-color: lightgray;">ลงทะเบียน</th>
            <th style="background-color: lightgray;">สิ้นสุด</th>
            <th style="background-color: lightgray;">Nation</th>
            <th style="background-color: lightgray;">Hn</th>
            <th style="background-color: lightgray;">Cid</th>
            <th style="background-color: lightgray;">Visit</th>
            <th style="background-color: lightgray;">ชื่อ-สกุล</th>
            <th style="background-color: lightgray;">อายุ</th>
            <th style="background-color: lightgray;">แผนก</th>
            <th style="background-color: lightgray;">สิทธิ์การรักษา</th>
            <th style="background-color: lightgray;">ค่ารักษา</th>
            <th style="background-color: lightgray;">Invoice</th>
            <th style="background-color: lightgray;">สถานะ</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($visitProvider->getModels() as $key => $value) : ?>
            <tr>
                <td><input type="checkbox" name="chkDel[]" id="chkDel<?= $key; ?>" value="<?= $value["visit_id"] . $value["hn"]; ?>"></td>
                <td class="badge"><?= $value["No"]; ?></td>
                <td class="text-nowrap" style="font-size: 14px;"><?= $value["regdate"]; ?></td>
                <td class="text-nowrap" style="font-size: 14px;"><?= $value["time_start"]; ?></td>
                <td class="text-nowrap" style="font-size: 14px;"><?= $value["time_end"]; ?></td>
                <td style="font-size: 14px; color: <?= $uuid_color ?? 'black'; ?>;"><?= $value["nation"]; ?></td>
                <td style="font-size: 14px;"><?= $value["hn"]; ?></td>
                <td style="font-size: 14px;"><?= $value["cid"]; ?></td>
                <td class="text-nowrap" style="font-size: 14px;"><?= $value["visit_id"]; ?></td>
                <td class="text-nowrap" style="font-size: 14px;"><?= $value["fullname"]; ?></td>
                <td style="font-size: 14px;"><?= $value["age"]; ?></td>
                <td class="text-nowrap" style="font-size: 14px;"><?= $value["unit_name"]; ?></td>
                <td class="text-nowrap" style="font-size: 14px;"><?= $value["inscl"]; ?></td>
                <td class="text-nowrap" style="font-size: 14px; color: green;"><?= $value["amount"]; ?></td>
                <td class="text-nowrap" style="font-size: 14px; color: orange;"><?= $value["invoice_number"]; ?></td>
                <td class="text-nowrap" style="font-size: 14px;"><?= $value["status"]; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<div class="box-footer with-border">
    <div class="col-md-12">
        <div class="form-group">
            <!-- Optional button -->
        </div>
    </div>
</div>

<?= Html::endForm(); ?>


<?= LinkPager::widget([
    'pagination' => $visitProvider->getPagination(),
]); ?>
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
echo Html::beginForm(['closevisit1/delete-multiple'], 'post'); // เริ่มต้นฟอร์ม POST สำหรับ multi-delete
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
<?php
$this->registerCss("
.centered-container {
    max-width: 1000px;
    margin: 0 auto;
    padding: 15px;
}

.zebra-table tbody tr:nth-child(odd) {
    background-color: #ffffff;
}
.zebra-table tbody tr:nth-child(even) {
    background-color: #e6f9e6;
}
.zebra-table tbody tr:hover {
    background-color: #c0f0c0 !important;
    cursor: pointer;
}

.table {
    width: 100%;
    font-size: 14px;
}

@media (max-width: 768px) {
    .table {
        font-size: 12px;
    }
}
");
?>