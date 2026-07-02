<?php
use yii;
use kartik\grid\GridView;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use yii\helpers\Url;
use yii\data\ArrayDataProvider;

use yii\data\ActiveDataProvider;

$this->title = 'NCD X-ray';
$this->params['breadcrumbs'][] = ['label' => 'รายงาน', 'url' => ['ntip/index']];
$this->params['breadcrumbs'][] = 'ntip ncd nap';
?>
<style>
    .cid-link {
        color: green;
    }
</style>

<br>
    <div style="background-color: #126F30; padding: 10px; border: 0px solid #D4F1F5;">
    <h4><a style="color: white;" href="#">ผู้มารับบริการแผนก OPO ER VIP IPD ที่มีการ X-ray และได้รับการตรวจ Creatinine(011) HbA1c(123) AFB(086,088) อายุมากว่า 65 ปี</h4>
</div>

<div class='well' style="background-color: #B7E1CD; color: darkgreen; border-radius: 10px;">
    <?php $form = ActiveForm::begin(); ?>
        <b style="font-size: 16px;"><i class="glyphicon glyphicon-calendar"></i> เลือกช่วงวันที่</b><br><br>
        
        ระหว่างวันที่:
        <?= yii\jui\DatePicker::widget([
            'name' => 'date1',
            'id' => 'date1', // ต้องมี ID สำหรับ JS
            'value' => $date1,
            'language' => 'th',
            'dateFormat' => 'yyyy-MM-dd',
            'options' => ['class' => 'form-control', 'style' => 'display:inline; width:140px; border: 1px solid #75c8fd;'],
            'clientOptions' => ['changeMonth' => true, 'changeYear' => true]
        ]); ?>
        
        ถึง:
        <?= yii\jui\DatePicker::widget([
            'name' => 'date2',
            'id' => 'date2',
            'value' => $date2,
            'language' => 'th',
            'dateFormat' => 'yyyy-MM-dd',
            'options' => ['class' => 'form-control', 'style' => 'display:inline; width:140px; border: 1px solid #75c8fd;'],
            'clientOptions' => ['changeMonth' => true, 'changeYear' => true]
        ]); ?>

        <button type="submit" class='btn btn-success' style="background-color: #004d40; border: none; margin-left: 5px;">
             ค้นหา
        </button>

        <div style="margin-top: 15px;">
            <span style="font-weight: bold;">ด่วน : </span>
            <button type="button" class="btn btn-default btn-sm" style="border-radius: 15px;" onclick="setDateRange(0)">วันนี้</button>
            <button type="button" class="btn btn-info btn-sm" style="border-radius: 15px;" onclick="setDateRange(7)">7 วัน</button>
            <button type="button" class="btn btn-success btn-sm" style="border-radius: 15px;" onclick="setDateRange(30)">1 เดือน</button>
            <button type="button" class="btn btn-warning btn-sm" style="border-radius: 15px;" onclick="setDateRange('thisMonth')">เดือนนี้</button>
            <button type="button" class="btn btn-primary btn-sm" style="border-radius: 15px;" onclick="setDateRange('thisYear')">ปีนี้</button>
        </div>
    <?php ActiveForm::end(); ?>
</div>

<?php
echo GridView::widget([
        'dataProvider' => $dataProvider,
        
        'panel' => [
            'before'=>'<b style="color:green">Ntip NAP Plus มีการ X-ray</b>',
            'after'=>'<b style="color:red">ประมวลผลจากวันที่ </b>'.$date1   .'<b style="color:red">ถึงวันที่</b>' .$date2 
          ],
               'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    [
                        'attribute' => 'regdate',
                        'header' => 'วันรับบริการ',
						'contentOptions' => ['style' => 'text-overflow: ellipsis; white-space: nowrap; max-width: 15vw; overflow: hidden;'],
						'headerOptions' => ['style' => 'background-color:#B7E1CD'],
                    ],
					[
                        'attribute' => 'hn',
                        'header' => 'hn',
						'headerOptions' => ['style' => 'background-color:#B7E1CD'],
                    ],
					[
						'attribute' => 'cid',
						'label' => 'เลขประชาชน',
						'contentOptions' => [
							'style' => 'text-overflow: ellipsis; white-space: nowrap; max-width: 25vw; overflow: hidden; font-size: 14px;',
							'ondblclick' => 'copyToClipboard(this);',
						],
						'headerOptions' => ['style' => 'background-color:#B7E1CD'],
					],

					[
                        'attribute' => 'fullname',
                        'header' => 'ชื่อ-สกุล',
						'contentOptions' => ['style' => 'text-overflow: ellipsis; white-space: nowrap; max-width: 15vw; overflow: hidden;'],
						'headerOptions' => ['style' => 'background-color:#B7E1CD'],
                    ],
					[
                        'attribute' => 'age',
                        'header' => 'อายุ',
						'headerOptions' => ['style' => 'background-color:#B7E1CD'],
                    ],
					[
                        'attribute' => 'unit_name',
                        'header' => 'แผนกรับบริการ',
						'headerOptions' => ['style' => 'background-color:#B7E1CD'],
                    ],
                    [
                        'attribute' => 'Diag',
                        'header' => 'รหัสโรค',
						'headerOptions' => ['style' => 'background-color:#B7E1CD'],
                    ],
                    
					[
                        'attribute' => 'lab_result',
                        'header' => 'HbA1c',
						'headerOptions' => ['style' => 'background-color:#B7E1CD'],
                    ],
					[
                        'attribute' => 'film_used',
                        'header' => 'ฟิมล์',
						'headerOptions' => ['style' => 'background-color:#B7E1CD'],
                    ],
					[
                        'attribute' => 'hosp_name',
                        'header' => 'สถานบริการ',
						'headerOptions' => ['style' => 'background-color:#B7E1CD'],
                    ],
					
                    ]
                    ]
                    );
                    
                    ?>
                <p>
			<?= Html::a('⏪ กลับหน้าหลัก', ['ntip/index3'], [
				'class' => 'btn btn-custom'
			]) ?>
			</p> 
                    
                   <!-- <div class="alert alert-info"><?=$sql?> </div>-->
<?php
$this->registerJs("
    // ฟังก์ชันสำหรับเลือกช่วงวันด่วน
    window.setDateRange = function(type) {
        var d2 = new Date(); // วันนี้
        var d1 = new Date();
        
        var format = function(date) {
            var year = date.getFullYear();
            var month = ('0' + (date.getMonth() + 1)).slice(-2);
            var day = ('0' + date.getDate()).slice(-2);
            return year + '-' + month + '-' + day;
        };

        if (type === 'thisMonth') {
            d1 = new Date(d2.getFullYear(), d2.getMonth(), 1); // วันแรกของเดือน
        } else if (type === 'thisYear') {
            d1 = new Date(d2.getFullYear(), 0, 1); // 1 มกราคมของปีนี้
        } else {
            d1.setDate(d2.getDate() - type); // ลบจำนวนวัน
        }

        $('#date1').val(format(d1));
        $('#date2').val(format(d2));
    };

    // ฟังก์ชัน Copy to Clipboard แบบปรับปรุง
    window.copyToClipboard = function(element) {
        var text = $(element).text();
        var temp = $('<input>');
        $('body').append(temp);
        temp.val(text).select();
        document.execCommand('copy');
        temp.remove();

        // แสดงผลว่า Copy แล้วโดยเปลี่ยนสีชั่วคราว
        var originalColor = element.style.color;
        element.style.color = 'red';
        setTimeout(function() {
            element.style.color = originalColor;
        }, 1500);
    };
");
?>