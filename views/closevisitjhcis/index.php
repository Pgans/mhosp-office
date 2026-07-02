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

$this->title = 'จองเคลม-JHCIS';
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
 <style>
    .btn-group-modern {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        justify-content: center;
        margin-top: 10px;
    }
    
    .btn-modern {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 14px 22px;
        border: none;
        border-radius: 12px;
        font-size: 16px;
        font-weight: 600;
        text-transform: uppercase;
        text-decoration: none;
        color: white;
        transition: all 0.3s ease-in-out;
        box-shadow: 0px 6px 14px rgba(0, 0, 0, 0.15);
        background-size: 200% auto;
    }

    .btn-modern i {
        font-size: 20px;
    }

    /* ปรับสีปุ่มให้สวยขึ้น */
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
        background: linear-gradient(135deg, #18abab, #35e8d7);
    }

    /* Hover Effect */
    .btn-modern:hover {
        transform: translateY(-4px);
        box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.25);
        background-position: right center;
    }

    /* ปรับให้ปุ่มดูโค้งและมีความนุ่มนวลแบบ iOS */
    .btn-modern:active {
        transform: translateY(2px);
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.15);
    }
</style>

<div class="btn-group-modern">
    
    <a href="<?= Url::to(['/closevisit1/index']) ?>" class="btn-modern btn-ipd">
        <i class="fa fa-check-square-o"></i> จองเก็บตก
    </a>
    <a href="<?= Url::to(['/logclosevisits/index']) ?>" class="btn-modern btn-refers">
        <i class="fa fa-check-square-o"></i> mBase-ตรวจการจอง
    </a>
    <a href="<?= Url::to(['/closevisitjhcis/index']) ?>" class="btn-modern btn-refers">
        <i class="fa fa-check-square-o"></i> Jhcis-จองเคลม
    </a>
    <a href="<?= Url::to(['/logclosevisitsj/index']) ?>" class="btn-modern btn-refers">
        <i class="fa fa-check-square-o"></i> Jhcis-ตรวจการจอง
    </a>
</div>

     <script type="text/javascript">
    setTimeout("frmMain.submit();",10000);
    //5000 อยากได้เร็วก็กำหนดตัวเลขน้อยๆเอานะ
    </script> 

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
            <!-- <div class="info-card bg-info" style="border: 4px solid gray;"> -->
             <div class="info-box" style="
    background: linear-gradient(to right, #d9f99d, #fcb6b1); /* Gradient from light green to light pink */
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
                        <?= Html::a('<i class="fa fa-check-square" aria-hidden="true"></i> ผ่าน', null, ['class' => 'btn btn-lightsuccess', 'id' => 'link1']) ?>
                            <img src="images/accept.svg" title="ข้อมูลสำเร็จ" width="10" height="10">
                        </div>
                    </div>
                </a>
            </div>
            <!-- /.info-box -->
        </div>

        <div class="col-xl-3 col-md-3 mb-3">
         <div class="info-box" style="
    background: linear-gradient(to right, #d9f99d, #fcb6b1); /* Gradient from light green to light pink */
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
                        <img src="images/reject.76840914.svg" title="ไม่ผ่าน" width="10" height="10">
                    </div>
                </a>
            </div>
        </div>


        <div class="col-xl-3 col-md-3 mb-3">
           <div class="info-box" style="
    background: linear-gradient(to right, #d9f99d, #fcb6b1); /* Gradient from light green to light pink */
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
                <a href="<?= Url::to(['closevisitjhcis/run-curl']) ?>" class="btn btn-lightwarning" style="font-size: 16px;">
                                    RunToken <i class="fa fa-arrow-circle-right"></i>
                                </a>
                <img src="images/waitmoph.98529de4.svg" title="ยอดทั้งหมด" width="10" height="10">
                    </div>
            </div>
            <!-- /.info-box -->
        </div>
       <div class="col-xl-3 col-md-3 mb-3">
             <div class="info-box" style="
    background: linear-gradient(to right, #d9f99d, #fcb6b1); /* Gradient from light green to light pink */
    padding: 20px; /* Optional: adjust padding as needed */
    border-radius: 8px; /* Optional: adjust border radius for rounded corners */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Optional: add shadow for better appearance */
    color: #333; /* Optional: text color */
">
               <span class="info-box-icon"><i class="far fa-calendar-check"style="color: green;"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text" style="color: green; font-size: 18px;">ยอดบริการวันนี้</span>
                    <span class="info-box-number"><?php echo $todayx ?></span>
                </div>
				 <div style="text-align: right;">
                
				</div>
				
                <div style="text-align: right;">
                 <a href="<?= Url::to(['closevisitjhcis/run-curl']) ?>" class="btn btn-lightwarning" style="font-size: 16px;">
                                    RunToken <i class="fa fa-arrow-circle-right"></i>
                                </a>
                <img src="images/waitmoph.98529de4.svg" title="ยอดทั้งหมด" width="10" height="10">
                    </div>
            </div>
            <!-- /.info-box -->
        </div>

        <div id="loading-spinner" style="display: none; color: purple; font-size: 100px;">
            <!-- Customize the style as needed -->
            <div class="custom-spinner"></div>
        </div>

        <?= Html::beginForm(['closevisitjhcis/check'], 'post', ['name' => 'frmMain']); ?>

        <!-- <form id="checkbo" name="frmMain" action="index" method="post"> -->
        <input name="btnButton1" class="btn btn-success btn btn-block" id="selectAll" type="submit" name="selectAll" value="ส่งข้อมูลจองเคลม-JHCIS" style="background-color: #f5a09a; border: 4px solid #dadada;">

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
                    <div align="center" style="font-size: 14px;"> uuid </div>
                </td>
                <td width="30" style="background-color: lightgray;">
                    <div align="left" style="font-size: 14px;"> Hn </div>
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
                <td width="30" style="background-color: lightgray;">
                    <div align="left" style="font-size: 14px;">การตรวจ </div>
                </td>  
                <td width="30" style="background-color: lightgray;">
                    <div align="left" class="text-nowrap" style="font-size: 14px;"> สิทธิ์การรักษา </div>
                </td>
                <td width="30" style="background-color: lightgray;">
                    <div align="left" style="font-size: 14px;">โรคหลัก
                </td>
                
                <td width="30" style="background-color: lightgray;">
                    <div align="left" style="font-size: 14px;">ค่ารักษา
                </td>
                <td width="30" style="background-color: lightgray;">
                    <div align="left" style="font-size: 14px;">invoice
                </td>
                <td width="30" style="background-color: lightgray;">
                    <div align="left" style="font-size: 14px;"> authencode
                </td>
            </tr>
        <?php
    foreach ($visitProvider->getModels() as $key => $value) :
        // กำหนดสีตามค่าของ uuid
        $uuid_color = ($value["uuid"] === 'Y') ? 'green' : 'red';
    ?>
                <tr>
				<!--<?php echo 'checked'; ?>-->

                    <td><input type="checkbox" name="chkDel[]"  id="chkDel<?= $i; ?>" <?php echo 'checked'; ?> value="<?php echo $value["visit_id"]; ?><?php echo $value["hn"]; ?>">
                    <td class="badge"><?php echo  $value["No"]; ?>
    </div>
    </td>
    <td class="text-nowrap" style="font-size: 14px;"><?php echo $value["regdate"]; ?></div>
    </td>
	
    <td style="font-size: 14px; color: <?php echo $uuid_color; ?>;">
                <?php echo $value["uuid"]; ?>
            </td>
    <td style="font-size: 14px;"><?php echo $value["hn"]; ?></div>
    </td>
    <td class="text-nowrap" style="font-size: 14px;"><?php echo $value["visit_id"]; ?></td>
    <td class="text-nowrap" style="font-size: 14px;"><?php echo $value["fullname"]; ?></td>
    <td style="font-size: 14px;"><?php echo $value["age"]; ?></div>
    <td class="text-nowrap" style="font-size: 14px;"><?php echo $value["symptoms"]; ?></div>
    <td class="text-nowrap" style="font-size: 14px;"><?php echo $value["rightname"]; ?></div>
    </td>
    
    <td class="text-nowrap" style="font-size: 14px;"><?php echo $value["DIAGCODE"]; ?></div>
    </td>
    <td class="text-nowrap" style="font-size: 14px; color: green;">
        <?php echo $value["money1"]; ?>
    </td>
    <td class="text-nowrap" style="font-size: 14px; color: orange;">
        <?php echo $value["invoice_number"]; ?>
    </td>
    
    <td class="text-nowrap" style="font-size: 14px;"><?php echo $value["claimcode"]; ?></div>
    </td>
    </tr>
<?php endforeach; ?>
</table>
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
			'transaction_uid',
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
