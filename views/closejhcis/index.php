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


$this->title = 'ข้อมูลการปิดสิทธิ์ ศูนย์สุพภาพชุมชนม่วงสามสิบ';
$this->registerCss('
    .log-line { padding: 5px; }
');
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@iconify/iconify@latest/dist/iconify.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<style>
    .my-striped-table tr:nth-child(odd)  { background-color: #efefef; }
    .my-striped-table tr:nth-child(even) { background-color: white; }
    .my-striped-table tbody tr:hover     { background-color: rgba(144, 238, 144, 0.5); }
    .custom-hover tbody tr:hover         { background-color: #f5f5f5; }

    .btn-blink { animation: blink-animation 1s infinite; }
    @keyframes blink-animation {
        0%   { opacity: 1; }
        50%  { opacity: 0; }
        100% { opacity: 1; }
    }

    .visit-element { background-color: lightgreen; padding: 3px; margin-bottom: 3px; }

    .panel-custom                { background-color: #2f1c00; max-height: 200px; overflow-y: auto; }
    .panel-custom .panel-heading { color: #00aaff; }
    .panel-custom .panel-body    { color: #00aaff; }
    .panel-body                  { padding: 5px; }

    .custom-spinner {
        border: 16px solid #f3f3f3;
        border-top: 16px solid purple;
        border-radius: 50%;
        width: 120px; height: 120px;
        animation: spin 1s linear infinite;
    }
    @keyframes spin {
        0%   { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .code-block {
        font-family: "Courier New", Courier, monospace;
        background-color: #f5f5f5;
        padding: 5px;
        border: 1px solid #ddd;
    }

    #loading-spinner {
        position: fixed; top: 50%; left: 50%;
        transform: translate(-50%, -50%);
        display: none;
    }

    /* ===== INFO CARD (ลดขนาดลงครึ่งหนึ่ง) ===== */
    .info-card {
        padding: 10px 15px;        /* ลดจาก 20px */
        border-radius: 8px;        /* ลดจาก 10px */
        font-size: 15px;           /* ลดจาก 16px */
        box-shadow: 3px 3px 6px rgba(0, 0, 0, 0.2);
        display: flex;
        flex-direction: column;
        height: 100%;
        box-sizing: border-box;
        color: #000;
    }
    .info-card-pink  { background: linear-gradient(135deg, #fce4ec, #f8bbd0, #f48fb1); }
    .info-card-green { background: linear-gradient(135deg, #f0fdf4, #bbf7d0, #86efac); }

    .info-card .info-box-icon i  { font-size: 18px; }   /* ลดจาก default */
    .info-card .info-box-text    { font-size: 15px; }   /* ลดจาก 18px */
    .info-card .info-box-number  { font-size: 15px; }
    .info-card .btn              { font-size: 15px; padding: 3px 8px; }

    /* บังคับ row ให้ card สูงเท่ากัน */
    .row.equal-height                      { display: flex; flex-wrap: wrap; align-items: stretch; }
    .row.equal-height > [class*="col-"]    { display: flex; flex-direction: column; }

    /* ลด margin bottom ของ col */
    .row.equal-height > [class*="col-"].mb-3 { margin-bottom: 8px !important; }

    /* ลดขนาด form ใน card 4 */
    .info-card .form-control-sm  { font-size: 15px; height: calc(1.5em + 0.5rem + 2px); padding: 0.25rem 0.4rem; }
    .info-card .col-form-label   { font-size: 15px; padding-top: 0.3rem; padding-bottom: 0.3rem; }
    .info-card .form-group       { margin-bottom: 4px; }
    .info-card .btn-sm           { font-size: 11px; padding: 2px 8px; }
</style>

<body>

    <script>
        function ClickCheckAll(vol) {
            var checkboxes = document.frmMain.querySelectorAll('input[name="chkDel[]"]');
            checkboxes.forEach(function(checkbox) {
                checkbox.checked = vol.checked;
            });
        }
    </script>

    <!-- ===== CARDS ROW ===== -->
    <div class="row equal-height">

        <!-- Card 1: ยอดบริการ → ชมพู -->
        <div class="col-xl-3 col-md-3 mb-3">
            <div class="info-card info-card-pink">
                <div style="display:flex; align-items:center; gap:6px; margin-bottom:4px;">
                    <i class="far fa-calendar-check" style="color:green; font-size:18px;"></i>
                    <span class="info-box-text" style="color:green; font-weight:bold;">
                        ยอดบริการ <?php echo "ทั้งหมด: $todayopd"; ?> ราย
                    </span>
                </div>
                <div>
                    <?php
                    echo "<span>ขอAuthen: </span><span style='color:green;'>$authen</span> | ";
                    echo "<span style='color:orange;'>เหลือ: $noauthen</span>";
                    ?>
                </div>
                <div>
                    <?php echo "<span>ต่างด้าว: </span><span style='color:green;'>$alien</span>"; ?> |
                    <?php echo "<span>admit: </span><span style='color:green;'>$todayipd</span>"; ?> |
                    <?php echo "<span>hd: </span><span style='color:green;'>$hd</span>"; ?>
                </div>
            </div>
        </div>

        <!-- Card 2: ข้อมูลปิดสิทธิ์ → เขียว -->
        <div class="col-xl-3 col-md-3 mb-3">
            <div class="info-card info-card-green">
                <div style="display:flex; align-items:center; gap:6px; margin-bottom:4px;">
                    <i class="fa-sharp fa-solid fa-compass" style="color:red; font-size:18px;"></i>
                    <span class="info-box-text" style="color:red; font-weight:bold;">ข้อมูลปิดสิทธิ์</span>
                </div>
                <div>
                    <?php
                    echo "<span>ปิดสิทธิ์: </span><span style='color:green;'>$closevisits</span> | ";
                    echo "<span style='color:orange;'>เหลือ: $noclosevisit</span>";
                    ?>
                </div>
                <div class="mt-auto" style="text-align:right; padding-top:6px;">
                    <a href="<?= Url::to(['closepcu/run-curl']) ?>" class="btn btn-info btn-sm">
                        รันToken-FDHปิดสิทธิ์ <i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Card 3: JHCIS → เขียว -->
        <div class="col-xl-3 col-md-3 mb-3">
            <div class="info-card info-card-green">
                <div style="display:flex; align-items:center; gap:6px; margin-bottom:4px;">
                    <i class="fas fa-hand-holding-heart" style="color:orange; font-size:18px;"></i>
                    <span style="color:green; font-weight:bold;">
                        JHCIS <?php echo "ทั้งหมด: " . (isset($visitj) ? $visitj : 0); ?> ราย
                    </span>
                </div>
                <div>
                    <?php
                    echo "<strong>จองเคลม:</strong> <span style='color:green;'>" . (isset($jongclaimj) ? $jongclaimj : 0) . "</span> | ";
                    echo "<strong>เหลือ:</strong> <span style='color:orange;'>" . (isset($nojongclaimj) ? $nojongclaimj : 0) . "</span>";
                    ?>
                </div>
                <div>
                    <?php
                    echo "<strong>authen:</strong> <span style='color:green;'>" . (isset($authenj) ? $authenj : 0) . "</span> | ";
                    echo "<strong>เหลือ:</strong> <span style='color:orange;'>" . (isset($noauthenj) ? $noauthenj : 0) . "</span>";
                    ?>
                </div>
                <div>
                    <?php
                    echo "<strong>ปิดสิทธิ์:</strong> <span style='color:green;'>" . (isset($closevisitj) ? $closevisitj : 0) . "</span> | ";
                    echo "<strong>เหลือ:</strong> <span style='color:orange;'>" . (isset($noclosevisitj) ? $noclosevisitj : 0) . "</span>";
                    ?>
                </div>
            </div>
        </div>

        <!-- Card 4: DatePicker Form → ชมพู -->
        <div class="col-xl-3 col-md-3 mb-3">
            <div class="info-card info-card-pink">
                <?php $form = ActiveForm::begin(['action' => ['closejhcis/index']]); ?>
                    <div class="form-group row mb-1">
                        <label class="col-sm-4 col-form-label text-right">วันที่:</label>
                        <div class="col-sm-8">
                            <?= yii\jui\DatePicker::widget([
                                'name'       => 'date1',
                                'value'      => Yii::$app->request->post('date1', date('Y-m-d')),
                                'language'   => 'th',
                                'dateFormat' => 'yyyy-MM-dd',
                                'options'    => [
                                    'class'       => 'form-control form-control-sm',
                                    'placeholder' => 'เลือกวันที่เริ่มต้น',
                                ],
                                'clientOptions' => ['changeMonth' => true, 'changeYear' => true],
                            ]); ?>
                        </div>
                    </div>
                    <div class="form-group row mb-1">
                        <label class="col-sm-4 col-form-label text-right">ถึง:</label>
                        <div class="col-sm-8">
                            <?= yii\jui\DatePicker::widget([
                                'name'       => 'date2',
                                'value'      => Yii::$app->request->post('date2', date('Y-m-d')),
                                'language'   => 'th',
                                'dateFormat' => 'yyyy-MM-dd',
                                'options'    => [
                                    'class'       => 'form-control form-control-sm',
                                    'placeholder' => 'เลือกวันที่สิ้นสุด',
                                ],
                                'clientOptions' => ['changeMonth' => true, 'changeYear' => true],
                            ]); ?>
                        </div>
                    </div>
                    <div class="mt-auto text-right pt-1">
                        <button type="submit" class="btn btn-danger btn-sm">ตกลง</button>
                    </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>

    </div><!-- end .row.equal-height -->

</body>

	
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
$url = Url::to(['closjhcis/read-smart-card']);
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

<div style="overflow: auto; height: 600px; border: 1px solid #ddd;">
    <table class="table my-striped-table" width="1000" border="0" bordercolor="#ddd"
        style="border-collapse: collapse; box-shadow: 2px 2px 5px rgba(0,0,0,0.2);">
        <thead style="position: sticky; top: 0; background-color: #fff; z-index: 1;">
            <tr>
                <th>#</th>
                <th>วันที่</th>
                <th>เลขบริการ</th>
                <th>cid</th>
                <th>Hn</th>
                <th>Authen</th>
                <th>Enpoint</th>
                <th>ชื่อ-สกุล</th>
                <th>อายุ</th>
				<th>น้้ำหนัก</th>
				<th>ส่วนสูง</th>
                <th>โทร</th>
                <th>รหัสโรค</th>
                <th>สิทธิ์</th>
                <th>ค่ารักษา</th>
                <th>ยืนยันตัวตน</th>
                <th>ปิดสิทธิ์</th>
                <th>ดึงข้อมูล</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($visitProvider && $visitProvider->getCount() > 0): ?>
                <?php foreach ($visitProvider->getModels() as $key => $value): ?>
                    <tr data-visit="<?= $value["visit"] ?>">
                        <td><?= $value["No"] ?></td>
                        <td><?= $value["regdate"] ?></td>
                        <td><?= $value["visit"] ?></td>
                        <td><?= substr_replace($value["cid"], "xx", -2) ?></td>
                        <td><?= $value["pid"] ?></td>
                        <td style="color: orange;"><?= $value["claimcode"] ?></td>
                        <td style="color: green;"><?= $value["enpoint"] ?></td>
                        <td><?= $value["fullname"] ?></td>
                        <td><?= $value["age"] ?></td>
						<td><?= $value["weight"] ?></td>
						<td><?= $value["height"] ?></td>
                       <?php
						$tel = trim($value["telephone"]);
						$isValid = preg_match('/^[0-9]{10}$/', $tel);
						?>

						<td>
							<span style="
								display:inline-block;
								padding:4px 10px;
								border-radius:20px;
								font-weight:bold;
								color:<?= $isValid ? '#0f5132' : '#842029' ?>;
								background:<?= $isValid ? '#d1e7dd' : '#f8d7da' ?>;
								border:1px solid <?= $isValid ? '#badbcc' : '#f5c2c7' ?>;
							">
								<?= htmlspecialchars($tel) ?>
							</span>
						</td>
                        <td><?= $value["DIAGCODE"] ?></td>
                        <td><?= $value["rightname"] ?></td>
                        <td><?= $value["money1"] ?></td>

                        <!-- ปุ่ม authen -->
                        <td>
                            <?= Html::beginForm(['closejhcis/check1'], 'post', [
                                'id'    => 'fdhForm1_' . $key,
                                'class' => 'fdhForm1',
                            ]) ?>
                                <?= Html::hiddenInput('visit', $value["visit"], ['class' => 'visitInput1']) ?>
                                <?= Html::submitButton('authen', [
                                    'class' => 'btn btn-danger authen-btn',
                                    'style' => 'background-color:#ab29c2; border:4px solid #dadada; padding:10px 20px; border-radius:30px; box-shadow:0 4px 6px rgba(0,0,0,0.1); font-size:1.2rem; text-transform:uppercase; cursor:pointer;',
                                ]) ?>
                            <?= Html::endForm() ?>
                        </td>

                        <!-- ปุ่มปิดสิทธิ์ -->
                        <td>
                            <?= Html::beginForm(['closejhcis/check'], 'post', [
                                'id'    => 'fdhForm_' . $key,
                                'class' => 'fdhForm',
                            ]) ?>
                                <?= Html::hiddenInput('visit', $value["visit"], ['class' => 'visitInput']) ?>
                                <?= Html::submitButton('ปิดสิทธิ์', [
                                    'class' => 'btn btn-danger close-btn',
                                    'style' => 'background-color:#00a400; border:4px solid #dadada; padding:10px 20px; border-radius:30px; box-shadow:0 4px 6px rgba(0,0,0,0.1); font-size:1.2rem; text-transform:uppercase; cursor:pointer; width:auto;',
                                ]) ?>
                            <?= Html::endForm() ?>
                        </td>

                        <!-- ปุ่ม GET -->
                        <td>
                            <?= Html::a('GET', ['closejhcis/check-nhso',
                                'cid'       => $value['cid'],
                                'visit_id'  => $value['visit'],
                                'telephone' => $value['telephone'],
                                'action'    => 'get',
                            ], [
                                'class' => 'btn btn-danger get-btn',
                                'style' => 'background-color:#0ba8bd; border:4px solid #dadada; padding:10px 20px; border-radius:30px; box-shadow:0 4px 6px rgba(0,0,0,0.1); font-size:1.2rem; text-transform:uppercase; cursor:pointer; width:auto;',
                            ]) ?>
                        </td>
                    </tr>
                <?php endforeach ?>
            <?php else: ?>
                <tr><td colspan="16">ไม่พบข้อมูล</td></tr>
            <?php endif ?>
        </tbody>
    </table>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const container = document.querySelector('div[style*="overflow: auto"]');
    const params    = new URLSearchParams(window.location.search);

    const visitIdFromUrl = params.get('visit_id') || params.get('visit');
    const actionFromUrl  = params.get('action');

    function scrollToVisit(visit) {
        if (!visit) return;
        const targetRow = document.querySelector('tr[data-visit="' + visit + '"]');
        if (targetRow && container) {
            container.scrollTop = targetRow.offsetTop - container.offsetTop;
            targetRow.style.backgroundColor = '#ffff99';
            setTimeout(() => { targetRow.style.backgroundColor = ''; }, 3000);
        } else if (targetRow) {
            targetRow.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }

    if (visitIdFromUrl && actionFromUrl) {
        scrollToVisit(visitIdFromUrl);
        sessionStorage.setItem('lastVisit',  visitIdFromUrl);
        sessionStorage.setItem('lastAction', actionFromUrl);
    } else {
        const lastVisit  = sessionStorage.getItem('lastVisit');
        const lastAction = sessionStorage.getItem('lastAction');
        if (lastVisit && lastAction) scrollToVisit(lastVisit);
    }

    // ปิดสิทธิ์
   
		document.querySelectorAll('.fdhForm').forEach(form => {
			form.addEventListener('submit', function (e) {
				e.preventDefault();

				const visitVal = this.querySelector('.visitInput').value;
				const btn      = this.querySelector('.close-btn');
				const row      = this.closest('tr');

				btn.disabled    = true;
				btn.textContent = '⏳ กำลังส่ง...';

				fetch(this.action, {
					method:  this.method,
					body:    new FormData(this),
					headers: { 'X-Requested-With': 'XMLHttpRequest' },
				})
				.then(res => res.json())
				.then(data => {
					if (data.success) {
						btn.textContent           = '✅ สำเร็จ';
						btn.style.backgroundColor = '#00a400';

						const url = new URL(window.location.href);
						url.searchParams.set('visit_id', visitVal);
						url.searchParams.set('action', 'close');
						setTimeout(() => { window.location.href = url.toString(); }, 1000);
					} else {
						alert(data.message);
						btn.disabled    = false;
						btn.textContent = 'ปิดสิทธิ์';
					}
				})
				.catch(err => {
					alert('❌ เกิดข้อผิดพลาด: ' + err.message);
					btn.disabled    = false;
					btn.textContent = 'ปิดสิทธิ์';
				});
			});
		});
    // GET
    document.querySelectorAll('.get-btn').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const href = this.getAttribute('href');
            const url  = new URL(href, window.location.origin);
            sessionStorage.setItem('lastVisit',  url.searchParams.get('visit_id'));
            sessionStorage.setItem('lastAction', url.searchParams.get('action'));
            window.location.href = href;
        });
    });

    // authen
    document.querySelectorAll('.fdhForm1').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const visitVal = this.querySelector('.visitInput1').value;
            const btn      = this.querySelector('.authen-btn');
            const row      = this.closest('tr');

            btn.disabled    = true;
            btn.textContent = '⏳ กำลังส่ง...';

            fetch(this.action, {
                method:  this.method,
                body:    new FormData(this),
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const claimCell = row.querySelector('td:nth-child(6)');
                    if (claimCell) {
                        claimCell.textContent = data.claimCode;
                        claimCell.style.color = 'green';
                    }
                    btn.textContent          = '✅ สำเร็จ';
                    btn.style.backgroundColor = '#00a400';

                    const url = new URL(window.location.href);
                    url.searchParams.set('visit_id', visitVal);
                    url.searchParams.set('action', 'authen');
                    setTimeout(() => { window.location.href = url.toString(); }, 1000);
                } else {
                    alert(data.message);
                    btn.disabled    = false;
                    btn.textContent = 'authen';
                }
            })
            .catch(err => {
                alert('❌ เกิดข้อผิดพลาด: ' + err.message);
                btn.disabled    = false;
                btn.textContent = 'authen';
                console.error(err);
            });
        });
    });
});
</script>
       
<p>
    <?= Html::a('⏪ กลับหน้าหลัก', ['nhso/index3'], [
        'class' => 'btn btn-custom'
    ]) ?>
</p>



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