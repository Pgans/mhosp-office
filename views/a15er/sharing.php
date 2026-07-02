<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;

$this->title = 'รายงานการครองเตียง';
?>

<style>
:root {
    --green-light:  #e1f5ee;
    --green-mid:    #5dcaa5;
    --green-dark:   #0f6e56;
    --green-border: #9fe1cb;
    --purple-light: #eeedfe;
    --purple-mid:   #7f77dd;
    --purple-dark:  #3c3489;
    --purple-border:#afa9ec;
    --text-main:    #1a1a2e;
    --text-muted:   #534ab7;
    --bg-page:      #f4fdf9;
    --card-bg:      #ffffff;
    --border:       #d0f0e4;
}

body { background: var(--bg-page) !important; font-size: 20px !important; }

/* ===== Header ===== */
.sh-header {
    background: linear-gradient(120deg, var(--green-dark) 0%, var(--green-mid) 60%, var(--purple-mid) 100%);
    border-radius: 18px;
    padding: 30px 36px;
    margin-bottom: 28px;
    color: #fff;
    display: flex;
    align-items: center;
    gap: 20px;
}
.sh-header .sh-icon {
    width: 64px; height: 64px;
    background: rgba(255,255,255,0.2);
    border-radius: 16px;
    display: flex; align-items: center; justify-content: center;
    font-size: 32px;
}
.sh-header h4  { margin: 0; font-size: 28px; font-weight: 700; }
.sh-header p   { margin: 6px 0 0; font-size: 18px; opacity: 0.88; }

/* ===== Filter Card ===== */
.sh-filter {
    background: var(--card-bg);
    border-radius: 16px;
    padding: 24px 28px;
    margin-bottom: 24px;
    border: 1.5px solid var(--green-border);
    box-shadow: 0 2px 14px rgba(93,202,165,0.10);
}
.sh-filter-title {
    font-size: 16px; font-weight: 700;
    color: var(--green-dark);
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 18px;
}

/* ===== Shortcut Buttons ===== */
.sh-shortcuts { display: flex; gap: 12px; flex-wrap: wrap; margin-bottom: 20px; }
.sh-shortcuts .sh-btn {
    padding: 10px 26px;
    border-radius: 50px;
    border: 2px solid var(--green-border);
    background: var(--green-light);
    color: var(--green-dark);
    font-size: 18px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    text-decoration: none;
}
.sh-shortcuts .sh-btn:hover,
.sh-shortcuts .sh-btn.active {
    background: var(--green-dark);
    border-color: var(--green-dark);
    color: #fff;
    box-shadow: 0 4px 14px rgba(15,110,86,0.25);
    transform: translateY(-2px);
}

/* ===== Date Row ===== */
.sh-date-row {
    display: flex; align-items: center; gap: 16px; flex-wrap: wrap;
}
.sh-date-row label {
    font-size: 18px; font-weight: 700; color: var(--text-main);
}
.sh-date-row input[type=text] {
    border: 2px solid var(--purple-border);
    border-radius: 10px;
    padding: 10px 16px;
    font-size: 18px;
    color: var(--text-main);
    transition: border-color 0.2s;
    width: 170px;
}
.sh-date-row input[type=text]:focus {
    border-color: var(--purple-mid); outline: none;
    box-shadow: 0 0 0 3px rgba(127,119,221,0.15);
}
.sh-btn-search {
    background: linear-gradient(120deg, var(--purple-mid), var(--purple-dark));
    color: #fff; border: none;
    border-radius: 10px;
    padding: 12px 28px;
    font-size: 18px; font-weight: 700;
    cursor: pointer;
    transition: opacity 0.2s, transform 0.1s;
    box-shadow: 0 4px 14px rgba(83,74,183,0.25);
}
.sh-btn-search:hover { opacity: 0.9; transform: translateY(-2px); }

/* ===== Summary Cards ===== */
.sh-summary-row { display: flex; gap: 14px; flex-wrap: wrap; margin-bottom: 24px; }
.sh-sum-card {
    flex: 1; min-width: 120px;
    background: var(--card-bg);
    border-radius: 14px;
    padding: 18px 14px;
    text-align: center;
    border-top: 5px solid var(--green-mid);
    border-bottom: 1.5px solid var(--border);
    border-left: 1.5px solid var(--border);
    border-right: 1.5px solid var(--border);
    box-shadow: 0 2px 12px rgba(93,202,165,0.08);
}
.sh-sum-card.lr  { border-top-color: #ef476f; }
.sh-sum-card.w1  { border-top-color: var(--purple-mid); }
.sh-sum-card.w2  { border-top-color: #5dcaa5; }
.sh-sum-card.w3  { border-top-color: #ffd166; }
.sh-sum-card.w4  { border-top-color: #afa9ec; }
.sh-sum-card.w5  { border-top-color: #9fe1cb; }
.sh-sum-card.tot {
    border-top-color: var(--purple-dark);
    background: var(--purple-light);
}
.sh-sum-card .val {
    font-size: 42px; font-weight: 800;
    color: var(--green-dark); line-height: 1;
}
.sh-sum-card.tot .val { color: var(--purple-dark); }
.sh-sum-card .lbl {
    font-size: 15px; color: var(--text-muted);
    margin-top: 6px; font-weight: 700;
    text-transform: uppercase; letter-spacing: 1px;
}

/* ===== Grid wrapper ===== */
.sh-grid-wrap {
    background: var(--card-bg);
    border-radius: 16px;
    overflow: hidden;
    border: 1.5px solid var(--green-border);
    box-shadow: 0 2px 14px rgba(93,202,165,0.10);
}

/* Grid panel header */
.sh-grid-wrap .panel-before,
.sh-grid-wrap .kv-panel-before {
    background: var(--green-dark) !important;
    color: #fff !important;
    padding: 16px 22px !important;
    font-size: 20px !important;
    font-weight: 700 !important;
    border-radius: 0 !important;
}

/* Table head */
.sh-grid-wrap table.kv-grid-table thead tr th,
.sh-grid-wrap table thead tr th {
    background: var(--purple-mid) !important;
    color: #fff !important;
    font-size: 18px !important;
    font-weight: 700 !important;
    text-align: center !important;
    padding: 14px 12px !important;
    border: none !important;
    letter-spacing: 0.5px;
}

/* Table body */
.sh-grid-wrap table.kv-grid-table tbody tr td,
.sh-grid-wrap table tbody tr td {
    font-size: 18px !important;
    text-align: center !important;
    vertical-align: middle !important;
    padding: 14px 12px !important;
    color: var(--text-main) !important;
    border-bottom: 1.5px solid var(--green-border) !important;
}
.sh-grid-wrap table tbody tr:nth-child(even) td {
    background: var(--green-light) !important;
}
.sh-grid-wrap table tbody tr:nth-child(odd) td {
    background: var(--purple-light) !important;
}
.sh-grid-wrap table tbody tr:hover td {
    background: #d0f0e4 !important;
}

/* Total column */
.sh-grid-wrap table tbody tr td.td-total {
    font-size: 20px !important;
    font-weight: 800 !important;
    color: var(--purple-dark) !important;
}

/* Badge */
.sh-badge {
    display: inline-flex; align-items: center; gap: 6px;
    background: var(--purple-light);
    color: var(--purple-dark);
    border: 1.5px solid var(--purple-border);
    border-radius: 50px; padding: 5px 16px;
    font-size: 16px; font-weight: 700;
}

/* SQL debug */
.sh-sql-box {
    background: #1e1b4b; color: #c7d2fe;
    border-radius: 12px; padding: 16px 20px;
    font-size: 15px; font-family: monospace;
    overflow-x: auto; margin-top: 20px;
    border: 1.5px solid var(--purple-border);
}
summary {
    cursor: pointer; color: var(--text-muted);
    font-size: 16px; font-weight: 600;
    margin-top: 20px; margin-bottom: 8px;
    user-select: none;
}
</style>

<!-- ===== Page Header ===== -->
<div class="sh-header">
    <div class="sh-icon">🏥</div>
    <div>
        <h4>รายงานการครองเตียง ประจำวัน</h4>
        <p>โรงพยาบาลม่วงสามสิบ &nbsp;|&nbsp; 
           ข้อมูล ณ วันที่ <?= date('d/m/') . (date('Y') + 543) ?>
        </p>
    </div>
</div>

<!-- ===== Filter Card ===== -->
<div class="sh-filter">
    <div class="sh-filter-title">🔍 เลือกช่วงเวลา</div>

    <?php $form = ActiveForm::begin(['method' => 'post']); ?>

    <div class="sh-shortcuts">
        <button type="submit" name="range" value="today"
            class="sh-btn <?= ($range == 'today') ? 'active' : '' ?>">
            📅 วันนี้
        </button>
        <button type="submit" name="range" value="7days"
            class="sh-btn <?= ($range == '7days') ? 'active' : '' ?>">
            📆 7 วันย้อนหลัง
        </button>
        <button type="submit" name="range" value="1month"
            class="sh-btn <?= ($range == '1month') ? 'active' : '' ?>">
            🗓 1 เดือนย้อนหลัง
        </button>
    </div>

    <div class="sh-date-row">
        <label>ระหว่างวันที่:</label>
        <?= yii\jui\DatePicker::widget([
            'name'       => 'date1',
            'value'      => $date1,
            'language'   => 'th',
            'dateFormat' => 'yyyy-MM-dd',
            'clientOptions' => ['changeMonth' => true, 'changeYear' => true],
        ]) ?>
        <label>ถึง:</label>
        <?= yii\jui\DatePicker::widget([
            'name'       => 'date2',
            'value'      => $date2,
            'language'   => 'th',
            'dateFormat' => 'yyyy-MM-dd',
            'clientOptions' => ['changeMonth' => true, 'changeYear' => true],
        ]) ?>
        <button type="submit" class="sh-btn-search">🔎 ค้นหา</button>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<?php
/* คำนวณ summary */
$allModels = $dataProvider->allModels;
$sumLR = $sumW1 = $sumW2 = $sumW3 = $sumW4 = $sumW5 = $sumTotal = 0;
foreach ($allModels as $row) {
    $sumLR    += $row['lr'];
    $sumW1    += $row['ward1'];
    $sumW2    += $row['ward2'];
    $sumW3    += $row['ward3'];
    $sumW4    += $row['ward4'];
    $sumW5    += $row['ward5'];
    $sumTotal += $row['total'];
}
?>

<!-- ===== Summary Cards ===== -->
<div class="sh-summary-row">
    <div class="sh-sum-card lr">
        <div class="val"><?= $sumLR ?></div>
        <div class="lbl">LR</div>
    </div>
    <div class="sh-sum-card w1">
        <div class="val"><?= $sumW1 ?></div>
        <div class="lbl">Ward 1</div>
    </div>
    <div class="sh-sum-card w2">
        <div class="val"><?= $sumW2 ?></div>
        <div class="lbl">Ward 2</div>
    </div>
    <div class="sh-sum-card w3">
        <div class="val"><?= $sumW3 ?></div>
        <div class="lbl">Ward 3</div>
    </div>
    <div class="sh-sum-card w4">
        <div class="val"><?= $sumW4 ?></div>
        <div class="lbl">Ward 4</div>
    </div>
    <div class="sh-sum-card w5">
        <div class="val"><?= $sumW5 ?></div>
        <div class="lbl">Ward 5</div>
    </div>
    <div class="sh-sum-card tot">
        <div class="val"><?= $sumTotal ?></div>
        <div class="lbl">รวมทั้งหมด</div>
    </div>
</div>

<!-- ===== Data Grid ===== -->
<div class="sh-grid-wrap">
<?php echo GridView::widget([
    'dataProvider' => $dataProvider,
    'panel' => [
        'before' =>
            '🛏 ตารางข้อมูลการครองเตียง &nbsp;
            <span style="font-size:17px;font-weight:400;opacity:0.88">
                ' . $date1 . ' ถึง ' . $date2 . '
            </span>
            <span style="float:right">
                <span class="sh-badge">👁 เข้าใช้งาน ' . $amount . ' ครั้ง</span>
            </span>',
    ],
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'attribute' => 'date_serv',
            'header'    => '📅 วันบริการ',
            'value'     => function($model) {
                $d = new DateTime($model['date_serv']);
                return $d->format('d/m/') . ($d->format('Y') + 543);
            },
        ],
        ['attribute' => 'lr',    'header' => '🏥 LR'],
        ['attribute' => 'ward1', 'header' => 'Ward 1'],
        ['attribute' => 'ward2', 'header' => 'Ward 2'],
        ['attribute' => 'ward3', 'header' => 'Ward 3'],
        ['attribute' => 'ward4', 'header' => 'Ward 4'],
        ['attribute' => 'ward5', 'header' => 'Ward 5'],
        [
            'attribute'      => 'total',
            'header'         => '📊 รวม',
            'contentOptions' => ['class' => 'td-total'],
        ],
    ],
]); ?>
</div>

<!-- SQL Debug -->
<details>
    <summary>🔧 SQL Query (debug)</summary>
    <div class="sh-sql-box"><?= Html::encode($sql) ?></div>
</details>