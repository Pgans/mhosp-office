<?php
use yii;
use kartik\grid\GridView;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use yii\helpers\Url;
use yii\data\ArrayDataProvider;
use kartik\export\ExportMenu;
use yii\data\ActiveDataProvider;

$this->title = 'ไฟล์นำเข้าโปรแกรม Ntip โรคเบาหวาน';
$this->params['breadcrumbs'][] = ['label' => 'รายงาน', 'url' => ['ntip/index']];
$this->params['breadcrumbs'][] = 'ntip ncd nap';
?>

<style>
/* ====== RESET & BASE ====== */
* { box-sizing: border-box; }

/* ====== PAGE HEADER ====== */
.page-header-card {
    background: linear-gradient(135deg, #0f9b8e 0%, #07a39e 50%, #2ec4b6 100%);
    border-radius: 14px;
    padding: 18px 24px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 14px;
    box-shadow: 0 4px 18px rgba(7,163,158,0.22);
}
.page-header-card .header-icon {
    width: 44px; height: 44px;
    background: rgba(255,255,255,0.2);
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 22px;
    flex-shrink: 0;
}
.page-header-card h4 {
    color: #fff;
    margin: 0;
    font-size: 17px;
    font-weight: 600;
    line-height: 1.4;
}
.page-header-card .sub {
    color: rgba(255,255,255,0.78);
    font-size: 13px;
    margin-top: 2px;
}

/* ====== FILTER CARD ====== */
.filter-card {
    background: linear-gradient(135deg, #f0fdf9 0%, #e6f9f5 100%);
    border: 1.5px solid #b2ede3;
    border-radius: 14px;
    padding: 20px 24px;
    margin-bottom: 20px;
}
.filter-card .filter-title {
    font-size: 13px;
    font-weight: 600;
    color: #0f9b8e;
    letter-spacing: 0.04em;
    text-transform: uppercase;
    margin-bottom: 14px;
    display: flex;
    align-items: center;
    gap: 6px;
}
.filter-row {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
}
.filter-label {
    font-size: 14px;
    color: #2d6a63;
    font-weight: 500;
    white-space: nowrap;
}
.filter-row input[type=text] {
    border: 1.5px solid #9edbd2;
    border-radius: 8px;
    padding: 7px 12px;
    font-size: 14px;
    color: #1a4a44;
    background: #fff;
    outline: none;
    transition: border-color .2s, box-shadow .2s;
    min-width: 140px;
}
.filter-row input[type=text]:focus {
    border-color: #07a39e;
    box-shadow: 0 0 0 3px rgba(7,163,158,0.13);
}
.divider-arrow {
    font-size: 18px;
    color: #0f9b8e;
}

/* Shortcut buttons */
.shortcut-group {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    margin-top: 14px;
    align-items: center;
}
.shortcut-label {
    font-size: 13px;
    color: #4a9e95;
    font-weight: 500;
}
.btn-shortcut {
    background: #fff;
    border: 1.5px solid #7dd5cc;
    color: #0b8a85;
    border-radius: 20px;
    padding: 5px 16px;
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    transition: all .18s;
    white-space: nowrap;
}
.btn-shortcut:hover, .btn-shortcut.active {
    background: linear-gradient(135deg, #07a39e, #2ec4b6);
    border-color: transparent;
    color: #fff;
    box-shadow: 0 2px 10px rgba(7,163,158,0.28);
}
.btn-shortcut:active { transform: scale(0.96); }

/* Submit button */
.btn-search {
    background: linear-gradient(135deg, #07a39e 0%, #2ec4b6 100%);
    color: #fff;
    border: none;
    border-radius: 9px;
    padding: 8px 22px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all .2s;
    box-shadow: 0 3px 12px rgba(7,163,158,0.3);
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.btn-search:hover {
    background: linear-gradient(135deg, #0ab5b0 0%, #38d1c2 100%);
    box-shadow: 0 5px 16px rgba(7,163,158,0.38);
    transform: translateY(-1px);
}
.btn-search:active { transform: translateY(0); }

/* ====== GRID WRAPPER ====== */
.grid-wrapper {
    background: #fff;
    border: 1px solid #d0ede9;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 2px 14px rgba(7,163,158,0.08);
    margin-bottom: 20px;
}

/* Grid overrides */
.kv-grid-table thead th {
    position: sticky !important;
    top: 0 !important;
    background: linear-gradient(180deg, #e0f5f1 0%, #caeee8 100%) !important;
    color: #0b6b65 !important;
    font-size: 12px !important;
    font-weight: 600 !important;
    letter-spacing: 0.03em !important;
    border-bottom: 2px solid #9edbd2 !important;
    z-index: 2 !important;
    white-space: nowrap;
}
.kv-grid-table tbody tr:hover td {
    background-color: #edfaf7 !important;
}
.kv-grid-table tbody td {
    font-size: 13px !important;
    color: #2b4b47 !important;
    border-color: #e8f5f2 !important;
    vertical-align: middle !important;
}
.kv-grid-table tbody tr:nth-child(even) td {
    background-color: #f7fefc !important;
}
.kv-grid-container {
    max-height: 520px;
    overflow-y: auto;
    border-radius: 0 0 14px 14px;
}
/* Panel styles */
.kv-panel-before {
    background: linear-gradient(90deg, #e8faf7 0%, #f2fdfb 100%) !important;
    border-bottom: 1px solid #c8ece6 !important;
    padding: 10px 16px !important;
    font-size: 14px !important;
    color: #0a7a74 !important;
    font-weight: 600 !important;
}
.kv-panel-after {
    background: #f8fefd !important;
    border-top: 1px solid #daf0ec !important;
    padding: 8px 16px !important;
    font-size: 13px !important;
}

/* ====== BACK BUTTON ====== */
.btn-back {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: linear-gradient(135deg, #f0fdf9, #e0f7f3);
    border: 1.5px solid #9edbd2;
    color: #0b8a85;
    border-radius: 10px;
    padding: 9px 20px;
    font-size: 14px;
    font-weight: 600;
    text-decoration: none !important;
    transition: all .2s;
    box-shadow: 0 2px 8px rgba(7,163,158,0.1);
}
.btn-back:hover {
    background: linear-gradient(135deg, #07a39e, #2ec4b6);
    color: #fff !important;
    border-color: transparent;
    box-shadow: 0 4px 14px rgba(7,163,158,0.3);
    transform: translateY(-1px);
}
.btn-back:active { transform: translateY(0); }
</style>

<!-- ====== PAGE HEADER ====== -->
<div class="page-header-card">
    <div class="header-icon">🩺</div>
    <div>
        <h4>ส่งออก Excel นำเข้า Ntip — โรคเบาหวาน</h4>
        <div class="sub">อายุน้อยกว่า 65 ปี · ระบบรายงาน NCD/NAP</div>
    </div>
</div>

<!-- ====== FILTER CARD ====== -->
<div class="filter-card">
    <div class="filter-title">
        <span>📅</span> กรองช่วงเวลา
    </div>

    <?php $form = ActiveForm::begin(); ?>

    <div class="filter-row">
        <span class="filter-label">ตั้งแต่วันที่</span>
        <?php
        echo yii\jui\DatePicker::widget([
            'name'       => 'date1',
            'value'      => $date1,
            'language'   => 'th',
            'dateFormat' => 'yyyy-MM-dd',
            'clientOptions' => ['changeMonth' => true, 'changeYear' => true],
        ]);
        ?>
        <span class="divider-arrow">→</span>
        <span class="filter-label">ถึงวันที่</span>
        <?php
        echo yii\jui\DatePicker::widget([
            'name'       => 'date2',
            'value'      => $date2,
            'language'   => 'th',
            'dateFormat' => 'yyyy-MM-dd',
            'clientOptions' => ['changeMonth' => true, 'changeYear' => true],
        ]);
        ?>
        <button type="submit" class="btn-search">
            <span>🔍</span> ค้นหา
        </button>
    </div>

    <!-- Shortcut buttons -->
    <div class="shortcut-group">
        <span class="shortcut-label">เลือกช่วงด่วน :</span>
        <button type="button" class="btn-shortcut" onclick="setDateRange(7)">7 วัน</button>
        <button type="button" class="btn-shortcut" onclick="setDateRange(30)">1 เดือน</button>
        <button type="button" class="btn-shortcut" onclick="setDateRange(90)">3 เดือน</button>
        <button type="button" class="btn-shortcut" onclick="setDateRange(180)">6 เดือน</button>
        <button type="button" class="btn-shortcut" onclick="setDateRange(365)">1 ปี</button>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<!-- ====== GRID ====== -->
<div class="grid-wrapper">
<?php
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'panel' => [
        'before' => '<span style="font-size:14px;font-weight:600;color:#0a7a74">📋 รายการนำเข้า Ntip — โรคเบาหวาน</span>',
        'after'  => '<span style="color:#e05c3a;font-weight:600">📆 ช่วงเวลา:</span> '
                  . '<span style="color:#2b4b47">' . $date1 . '</span>'
                  . '<span style="color:#888;margin:0 6px">ถึง</span>'
                  . '<span style="color:#2b4b47">' . $date2 . '</span>',
    ],
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],

        ['attribute' => 'RISK_TYPE',           'header' => 'RISK_TYPE',           'headerOptions' => ['style' => 'background:linear-gradient(180deg,#e0f5f1,#caeee8)']],
        ['attribute' => 'TITLE_ID',            'header' => 'TITLE_ID',            'headerOptions' => ['style' => 'background:linear-gradient(180deg,#e0f5f1,#caeee8)']],
        [
            'attribute'      => 'FNAME',
            'label'          => 'FNAME',
            'contentOptions' => ['style' => 'text-overflow:ellipsis;white-space:nowrap;max-width:22vw;overflow:hidden;font-size:13px;cursor:copy', 'ondblclick' => 'copyToClipboard(this)'],
            'headerOptions'  => ['style' => 'background:linear-gradient(180deg,#e0f5f1,#caeee8)'],
        ],
        ['attribute' => 'LNAME',               'header' => 'LNAME',               'headerOptions' => ['style' => 'background:linear-gradient(180deg,#e0f5f1,#caeee8)']],
        ['attribute' => 'CID',                 'header' => 'CID',                 'headerOptions' => ['style' => 'background:linear-gradient(180deg,#e0f5f1,#caeee8)']],
        ['attribute' => 'GENDER',              'header' => 'GENDER',              'headerOptions' => ['style' => 'background:linear-gradient(180deg,#e0f5f1,#caeee8)']],
        ['attribute' => 'BORN',                'header' => 'BORN',                'headerOptions' => ['style' => 'background:linear-gradient(180deg,#e0f5f1,#caeee8)']],
        ['attribute' => 'ADDR',                'header' => 'ADDR',                'headerOptions' => ['style' => 'background:linear-gradient(180deg,#e0f5f1,#caeee8)']],
        ['attribute' => 'MU',                  'header' => 'MU',                  'headerOptions' => ['style' => 'background:linear-gradient(180deg,#e0f5f1,#caeee8)']],
        ['attribute' => 'PROVINCE_ID',         'header' => 'PROVINCE_ID',         'headerOptions' => ['style' => 'background:linear-gradient(180deg,#e0f5f1,#caeee8)']],
        ['attribute' => 'AMPHUR_ID',           'header' => 'AMPHUR_ID',           'headerOptions' => ['style' => 'background:linear-gradient(180deg,#e0f5f1,#caeee8)']],
        ['attribute' => 'TAMBOL_ID',           'header' => 'TAMBOL_ID',           'headerOptions' => ['style' => 'background:linear-gradient(180deg,#e0f5f1,#caeee8)']],
        ['attribute' => 'PEOPLE_TYPE',         'header' => 'PEOPLE_TYPE',         'headerOptions' => ['style' => 'background:linear-gradient(180deg,#e0f5f1,#caeee8)']],
        ['attribute' => 'RACE_ID',             'header' => 'RACE_ID',             'headerOptions' => ['style' => 'background:linear-gradient(180deg,#e0f5f1,#caeee8)']],
        ['attribute' => 'CONTACT_DATE',        'header' => 'CONTACT_DATE',        'headerOptions' => ['style' => 'background:linear-gradient(180deg,#e0f5f1,#caeee8)']],
        ['attribute' => 'SYMPTOM_SCREEN',      'header' => 'SYMPTOM_SCREEN',      'headerOptions' => ['style' => 'background:linear-gradient(180deg,#e0f5f1,#caeee8)']],
        ['attribute' => 'CXR_DATE',            'header' => 'CXR_DATE',            'headerOptions' => ['style' => 'background:linear-gradient(180deg,#e0f5f1,#caeee8)']],
        ['attribute' => 'CXR_RESULT',          'header' => 'CXR_RESULT',          'headerOptions' => ['style' => 'background:linear-gradient(180deg,#e0f5f1,#caeee8)']],
        ['attribute' => 'CXR_ABNORMAL_RESULT', 'header' => 'CXR_ABNORMAL_RESULT', 'headerOptions' => ['style' => 'background:linear-gradient(180deg,#e0f5f1,#caeee8)']],
        ['attribute' => 'DX',                  'header' => 'DX',                  'headerOptions' => ['style' => 'background:linear-gradient(180deg,#e0f5f1,#caeee8)']],
        ['attribute' => 'HN',                  'header' => 'HN',                  'headerOptions' => ['style' => 'background:linear-gradient(180deg,#e0f5f1,#caeee8)']],
        ['attribute' => 'HMAIN_ID',            'header' => 'HMAIN_ID',            'headerOptions' => ['style' => 'background:linear-gradient(180deg,#e0f5f1,#caeee8)']],
        ['attribute' => 'INSCL_ID',            'header' => 'INSCL_ID',            'headerOptions' => ['style' => 'background:linear-gradient(180deg,#e0f5f1,#caeee8)']],
        ['attribute' => 'ICD10',               'header' => 'ICD10',               'headerOptions' => ['style' => 'background:linear-gradient(180deg,#e0f5f1,#caeee8)']],
        ['attribute' => 'HbA1C',               'header' => 'HbA1C',               'headerOptions' => ['style' => 'background:linear-gradient(180deg,#e0f5f1,#caeee8)']],
        ['attribute' => 'IMMUNNO_DISEASE',     'header' => 'IMMUNNO_DISEASE',     'headerOptions' => ['style' => 'background:linear-gradient(180deg,#e0f5f1,#caeee8)']],
        ['attribute' => 'B24',                 'header' => 'B24',                 'headerOptions' => ['style' => 'background:linear-gradient(180deg,#e0f5f1,#caeee8)']],
    ],
]);
?>
</div>

<!-- Back button -->
<div style="margin-bottom:20px">
    <?= Html::a('← กลับหน้าหลัก', ['ntip/index3'], ['class' => 'btn-back']) ?>
</div>

<script>
/* ---- Copy to clipboard ---- */
function copyToClipboard(el) {
    var ta = document.createElement('textarea');
    ta.value = el.innerText;
    document.body.appendChild(ta);
    ta.select();
    document.execCommand('copy');
    ta.remove();
    var orig = el.style.color;
    el.style.color = '#07a39e';
    el.style.fontWeight = '600';
    setTimeout(function() { el.style.color = orig; el.style.fontWeight = ''; }, 1200);
}

/* ---- Date shortcut (days back from today) ---- */
function setDateRange(days) {
    var today = new Date();
    var from  = new Date();
    from.setDate(today.getDate() - days + 1);

    function fmt(d) {
        var mm = String(d.getMonth()+1).padStart(2,'0');
        var dd = String(d.getDate()).padStart(2,'0');
        return d.getFullYear() + '-' + mm + '-' + dd;
    }

    /* kartik DatePicker stores value in hidden inputs named date1 / date2 */
    var inputs = document.querySelectorAll('input[name="date1"], input[name="date2"]');
    inputs.forEach(function(inp) {
        if (inp.name === 'date1') inp.value = fmt(from);
        if (inp.name === 'date2') inp.value = fmt(today);
        /* also update visible jQueryUI datepicker if present */
        try { $(inp).datepicker('setDate', inp.value); } catch(e) {}
    });

    /* highlight active button */
    document.querySelectorAll('.btn-shortcut').forEach(function(b){ b.classList.remove('active'); });
    event.target.classList.add('active');
}
</script>