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



$this->title = 'บริการส่งข้อมูลปิดสิทธิ์';
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
        background: linear-gradient(135deg, #6a11cb, #2575fc);
    }

    .btn-opd {
        background: linear-gradient(135deg, #0ba360, #3cba92);
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

      
        <?= Html::beginForm(['closefdh/check'], 'post', ['name' => 'frmMain']); ?>
		 <div class="floating-button" style="position: fixed; bottom: calc(3 * 2rem); left: 55%; transform: translateX(-45%); z-index: 1000;">
		<button type="submit" 
				name="btnButton1" 
				id="selectAll" 
				class="btn btn-success btn btn-block" 
				style="background-color: #00a400; border: 4px solid #dadada; padding: 10px 20px; border-radius: 30px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); font-size: 1.5rem; text-transform: uppercase; cursor: pointer; width: auto;">
			<i class="fa fa-arrow-circle-right" style="margin-right: 10px;"></i>
			ส่งข้อมูลปิดสิทธิ์
		</button>
	</div>
	 <!-- Spinner ที่จะหมุนเมื่อกดปุ่ม -->
    <div id="loading-spinner" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 2000;">
        <div class="custom-spinner"></div>
    </div>

<?= Html::endForm(); ?>

<!-- CSS สำหรับ Spinner -->
<style>
    .custom-spinner {
        border: 8px solid #f3f3f3; /* สีพื้นหลัง */
        border-top: 8px solid #00a400; /* สีของ Spinner */
        border-radius: 50%;
        width: 80px;
        height: 80px;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>

<!-- JavaScript ควบคุมการแสดงผล Spinner -->
<script>
    function showSpinner() {
        document.getElementById('loading-spinner').style.display = 'block';
        document.getElementById('submitBtn').disabled = true; // ปิดการใช้งานปุ่ม
        document.getElementById('submitBtn').innerHTML = 'กำลังส่งข้อมูล...';
    }
</script>

        <!-- <form id="checkbo" name="frmMain" action="index" method="post"> -->
      <!--  <input name="btnButton1" class="btn btn-success btn btn-block" id="selectAll" type="submit" name="selectAll" value="ส่งข้อมูล FDH Close Visits" style="background-color: #0fab42; border: 4px solid #dadada;">
         -->
        <table class="table table-striped" width="1000" border="0">
            <tr>
                <th width="30" style="background-color: lightgray;">
                    <div align="center">
                        <!-- <input name="CheckAll" type="checkbox" id="CheckAll" value="Y" onClick="ClickCheckAll(this);"> -->
                        <input name="CheckAll" type="checkbox" id="CheckAll" value="Y" onClick="ClickCheckAll(this);">
                        <!-- <input type="checkbox" id="selectAll"> -->
                    </div>
                <td width="30" style="background-color: lightgray;">
                    <div align="center"> # </div>
                </td>
                <td width="30" style="background-color: lightgray;">
                    <div align="center" style="font-size: 14px;"> วันที่ </div>
                </td>
                <td width="30" style="background-color: lightgray;">
                    <div align="left" style="font-size: 14px;"> Hn </div>
                </td>
                <td width="30" style="background-color: lightgray;">
                    <div align="left" style="font-size: 14px;"> Cid </div>
                </td>
				<td width="150" style="background-color: lightgray;">
                    <div align="left" style="font-size: 14px;"> Visit </div>
                </td>
                <td width="150" style="background-color: lightgray;">
                    <div align="left" style="font-size: 14px;"> ชื่อ-สกุล </div>
                </td>
                <td width="30" style="background-color: lightgray;">
                    <div align="left" style="font-size: 14px;">อายุ </div>
                </td>
                <td width="70" style="background-color: lightgray;">
                    <div align="left" style="font-size: 14px;">แผนก </div>
                </td>
                <td width="30" style="background-color: lightgray;">
                    <div align="left" class="text-nowrap" style="font-size: 14px;"> สิทธิ์การรักษา </div>
                </td>
                <td width="30" style="background-color: lightgray;">
                    <div align="left" style="font-size: 14px;">ค่ารักษา
                </td>
                <td width="30" style="background-color: lightgray;">
                    <div align="left" style="font-size: 14px;">authen
                </td>
                
            </tr>
        
            <?php foreach ($visitProvider->getModels() as $key => $value) : ?>
    
        <td><input type="checkbox" name="chkDel[]" id="chkDel<?= $i; ?>" value="<?php echo $value["visit_id"]; ?><?php echo $value["hn"]; ?>"></td>
        <td class="badge"><?php echo  $value["No"]; ?></td>
    
    </div>
    </td>
    <td class="text-nowrap" style="font-size: 14px;"><?php echo $value["regdate"]; ?></div>
    </td>
    <td style="font-size: 14px;"><?php echo $value["hn"]; ?></div></td>
    <td style="font-size: 14px;"><?php echo $value["cid"]; ?></div></td>
    <td class="text-nowrap" style="font-size: 14px;"><?php echo $value["visit"]; ?></td>
    <td class="text-nowrap" style="font-size: 14px;"><?php echo $value["fullname"]; ?></td>
    <td style="font-size: 14px;"><?php echo $value["age"]; ?></div>
    <td class="text-nowrap" style="font-size: 14px;"><?php echo $value["unit_name"]; ?></div>
    <td class="text-nowrap" style="font-size: 14px;"><?php echo $value["inscl_name"]; ?></div>
    </td>
    <td class="text-nowrap" style="font-size: 14px; color: green;">
        <?php echo $value["amount"]; ?>
    </td>
    <td class="text-nowrap" style="font-size: 14px; color: orange;">
        <?php echo $value["claimcode"]; ?>
    </td>  
   
    </tr>
<?php endforeach; ?>
</table>
<style>
    /* สลับสีแถว */
    .table-striped tr:nth-child(even) {
        background-color: #dff5e8; /* สีพื้นหลังแถวคู่ */
    }
    .table-striped tr:nth-child(odd) {
        background-color: #ffffff; /* สีพื้นหลังแถวคี่ */
    }

    /* เพิ่มลักษณะของแถวเมื่อ hover */
    .table-striped tr:hover {
        background-color: #b7e6f7; /* สีพื้นหลังเมื่อ hover */
    }

    /* สไตล์ของเซลล์ */
    .table td, .table th {
        padding: 10px;
        text-align: left;
        vertical-align: middle;
        border-top: 1px solid #ddd;
    }

    /* ปรับขนาดฟอนต์ */
    .table th {
        font-size: 14px;
        font-weight: bold;
    }

    .table td {
        font-size: 14px;
    }

    /* ปรับสีขอบตาราง */
    .table {
        border-collapse: collapse;
        width: 100%;
    }

    
</style>



<div class="box-footer with-border">
    <div class="col-md-12">
        <div class="form-group">
        
            <!-- <?= Html::button(Yii::t('app', 'Ht'), ['class' => 'btn btn-warning pull-right', 'id' => 'btn-delete']) ?> -->
        </div>
    </div>
</div>

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
