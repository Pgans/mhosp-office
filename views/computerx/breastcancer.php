<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
$this->title = 'M30hospital - Dashboard มะเร็งเต้านม';
?>

<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;500;600;700&family=Kanit:wght@500;600;700&display=swap" rel="stylesheet">

<style>
/* ปรับภาพรวมให้อ่านง่ายและชัดเจนขึ้น */
.db-wrap { font-family: 'Sarabun', sans-serif; font-weight: 500; color: #2D1752; padding: 20px 16px; max-width: 1100px; margin: 0 auto; }

.db-header { background: #fff; border-radius: 14px; border: 2px solid #DCD1EC; /* เพิ่มความเข้มของขอบ */
    padding: 24px 28px 20px; margin-bottom: 20px; display: flex; align-items: flex-start; gap: 16px; }
.db-header-icon { width: 48px; height: 48px; border-radius: 12px; background: #F3EEFB;
    display: flex; align-items: center; justify-content: center; font-size: 24px; flex-shrink: 0; }
.db-header h1 { font-family: 'Kanit', sans-serif; font-size: 22px; font-weight: 700; /* เพิ่มขนาดและน้ำหนัก */
    color: #2D1752; margin: 0 0 6px; }
.db-header p { font-size: 14px; color: #524375; font-weight: 500; margin: 0; } /* เพิ่มความเข้มข้อความย่อย */

.stat-row { display: flex; gap: 14px; margin-bottom: 20px; flex-wrap: wrap; }
.stat-card { flex: 1 1 140px; background: #fff; border-radius: 12px;
    border: 2px solid #E2DAF0; padding: 18px 16px 14px; position: relative; overflow: hidden; }
.stat-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0;
    height: 4px; background: var(--accent,#7C4DFF); border-radius: 12px 12px 0 0; }
.stat-card .s-label { font-size: 13px; font-weight: 700; color: #5B448F; /* เพิ่ม Contrast และขนาด */
    text-transform: uppercase; letter-spacing: .6px; margin-bottom: 8px; }
.stat-card .s-val { font-family: 'Kanit', sans-serif; font-size: 34px; font-weight: 700; /* ปรับตัวเลขให้หนาชัดเจน */
    color: #2D1752; line-height: 1; }
.stat-card .s-sub { font-size: 13px; color: #6D5B99; font-weight: 500; margin-top: 6px; } /* ปรับสีข้อความด้านล่างให้เข้มขึ้น */
.stat-card .s-icon { position: absolute; right: 14px; top: 18px; font-size: 22px; color: var(--accent,#7C4DFF); opacity: .35; }

.chart-card { background: #fff; border-radius: 14px; border: 2px solid #E2DAF0;
    padding: 20px 20px 16px; margin-bottom: 20px; }
.card-head { display: flex; align-items: center; gap: 10px; margin-bottom: 16px;
    padding-bottom: 12px; border-bottom: 2px solid #F0E9FC; }
.card-head-dot { width: 10px; height: 10px; border-radius: 50%; background: var(--dot,#7C4DFF); flex-shrink: 0; }
.card-head h3 { font-family: 'Kanit', sans-serif; font-size: 16px; font-weight: 700;
    color: #2D1752; margin: 0; }
.card-head .hint { margin-left: auto; font-size: 13px; color: #4A3670; font-weight: 600; } /* ปรับคำอธิบายกราฟให้ชัดเจนขึ้น */

.tbl-card { background: #fff; border-radius: 14px; border: 2px solid #E2DAF0;
    padding: 20px; margin-bottom: 20px; overflow-x: auto; }
.db-tbl { width: 100%; border-collapse: collapse; font-size: 14px; }
.db-tbl thead th { background: #EFE9F8; color: #2D1752; font-family: 'Kanit', sans-serif; /* ปรับพื้นหลังและสีหัวตาราง */
    font-weight: 700; font-size: 13px; padding: 12px 14px; text-align: center;
    border-bottom: 3px solid #D5C5F0; white-space: nowrap; }
.db-tbl thead th:first-child { border-radius: 8px 0 0 0; text-align: left; }
.db-tbl thead th:last-child  { border-radius: 0 8px 0 0; }
.db-tbl tbody tr:hover td { background: #F5EFFF; }
.db-tbl tbody td { padding: 12px 14px; text-align: center;
    border-bottom: 1px solid #EFE9F8; color: #2D1752; font-weight: 500; } /* ตัวอักษรเนื้อหาเข้มขึ้น */
.db-tbl tbody td:first-child { text-align: left; font-weight: 700; color: #5B2FA0; }

/* ปรับสีป้าย (Badge) ในตารางให้ตัวอักษรเข้มอ่านง่าย ชัดเจนทุกสี */
.bp { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 13px; font-weight: 700; min-width: 45px; text-align: center; }
.b1 { background: #E8DDFF; color: #3C0A93; } /* ลงทะเบียน */
.b2 { background: #CFF2DE; color: #0A5228; } /* ขอ Authen */
.b3 { background: #CEF3FA; color: #054B5B; } /* ซักประวัติ */
.b4 { background: #FFECC7; color: #5C3A00; } /* จ่ายยา */
</style>

<?php
$sql = "SELECT 
    DATE(k.REG_DATETIME) AS regdate,
    COUNT(k.visit_id) AS register,
    SUM(CASE WHEN k.claimcode <> '' THEN 1 ELSE 0 END) AS authen,
    SUM(CASE WHEN ISNULL(k.claimcode) OR k.claimcode = '' THEN 1 ELSE 0 END) AS no_authen,
    SUM(CASE WHEN k.history = '' OR ISNULL(k.history) THEN 1 ELSE 0 END) AS screen,
    SUM(CASE WHEN k.drug_id IS NOT NULL THEN 1 ELSE 0 END) AS drug
FROM 
    (SELECT 
         o.REG_DATETIME, o.visit_id, o.hn, o.unit_reg,
         u.unit_name, a.claimcode, d.drug_id, i.nickname, o.history
     FROM opd_visits o
     LEFT JOIN service_units u ON u.unit_id = o.unit_reg
     LEFT JOIN authen_kiosk a ON a.visit_id = o.visit_id
     LEFT JOIN prescriptions ps ON ps.visit_id = o.visit_id AND ps.is_cancel = 0
     LEFT JOIN drugs d ON d.drug_id = ps.drug_id
     LEFT JOIN opd_operations op ON op.visit_id = o.visit_id AND op.is_cancel = 0
     LEFT JOIN icd9cm i ON i.icd9 = op.icd9
     
     WHERE o.unit_reg = '90' AND o.is_cancel = 0
       AND o.REG_DATETIME BETWEEN '2026-06-08 00:01' AND '2026-06-09 23:59'
     GROUP BY o.visit_id
    ) AS k
GROUP BY DATE(k.REG_DATETIME)";

$rawData = Yii::$app->db4->createCommand($sql)->queryAll();

$totalRegister = array_sum(array_column($rawData, 'register'));
$totalAuthen   = array_sum(array_column($rawData, 'authen'));
$totalScreen   = array_sum(array_column($rawData, 'screen'));
$totalDrug     = array_sum(array_column($rawData, 'drug'));

$dates     = json_encode(array_column($rawData, 'regdate'));
$registers = json_encode(array_map('intval', array_column($rawData, 'register')));
$authens   = json_encode(array_map('intval', array_column($rawData, 'authen')));
$screens   = json_encode(array_map('intval', array_column($rawData, 'screen')));
$drugs     = json_encode(array_map('intval', array_column($rawData, 'drug')));
?>

<div class="db-wrap">

    <div class="db-header">
        <div class="db-header-icon">🏥</div>
        <div>
            <h1>Dashboard คัดกรองมะเร็งเต้านม</h1>
            <p>โรงพยาบาลม่วงสามสิบ (045489064) &nbsp;·&nbsp; 8–9 มิถุนายน 2569 &nbsp;·&nbsp; หน่วยบริการ OPD</p>
        </div>
    </div>

    <div class="stat-row">
        <div class="stat-card" style="--accent:#7C4DFF">
            <div class="s-icon">👥</div>
            <div class="s-label">ลงทะเบียนทั้งหมด</div>
            <div class="s-val"><?= number_format($totalRegister) ?></div>
            <div class="s-sub">ราย รวมทุกวัน</div>
        </div>
        <div class="stat-card" style="--accent:#22A05B">
            <div class="s-icon">✅</div>
            <div class="s-label">ขอ Authen</div>
            <div class="s-val"><?= number_format($totalAuthen) ?></div>
            <div class="s-sub">ราย</div>
        </div>
        <div class="stat-card" style="--accent:#0097B2">
            <div class="s-icon">📋</div>
            <div class="s-label">ซักประวัติ</div>
            <div class="s-val"><?= number_format($totalScreen) ?></div>
            <div class="s-sub">ราย</div>
        </div>
        <div class="stat-card" style="--accent:#E07B00">
            <div class="s-icon">💊</div>
            <div class="s-label">จ่ายยา</div>
            <div class="s-val"><?= number_format($totalDrug) ?></div>
            <div class="s-sub">ราย</div>
        </div>
    </div>

    <div class="chart-card">
        <div class="card-head" style="--dot:#7C4DFF">
            <div class="card-head-dot"></div>
            <h3>กราฟแท่ง + กราฟเส้น — สถิติและแนวโน้มการให้บริการ</h3>
            <span class="hint">📊 แท่ง = ลงทะเบียน &nbsp;|&nbsp; 📈 เส้น = Authen / ซักประวัติ / จ่ายยา</span>
        </div>
        <div id="chart-combo" style="height:400px;"></div>
    </div>

    <div class="tbl-card">
        <div class="card-head" style="--dot:#7C4DFF; border-bottom:1px solid #F2EDF9; margin-bottom:14px; padding-bottom:12px;">
            <div class="card-head-dot"></div>
            <h3>ตารางสรุปข้อมูลรายวัน</h3>
        </div>
        <table class="db-tbl">
            <thead>
                <tr>
                    <th>วันที่</th>
                    <th>ลงทะเบียน</th>
                    <th>ขอ Authen</th>
                    <th>ซักประวัติ</th>
                    <th>จ่ายยา</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rawData as $row): ?>
                <tr>
                    <td><?= Html::encode($row['regdate']) ?></td>
                    <td><span class="bp b1"><?= intval($row['register']) ?></span></td>
                    <td><span class="bp b2"><?= intval($row['authen']) ?></span></td>
                    <td><span class="bp b3"><?= intval($row['screen']) ?></span></td>
                    <td><span class="bp b4"><?= intval($row['drug']) ?></span></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>

<?php
$this->registerJs("
$(function () {
    Highcharts.chart('chart-combo', {
        chart: {
            backgroundColor: '#fff',
            style: { fontFamily: 'Sarabun, sans-serif', fontSize: '14px' },
            spacing: [16, 16, 16, 8]
        },
        title: { text: null },
        credits: { enabled: false },
        exporting: { enabled: true },
        xAxis: {
            categories: $dates,
            labels: { style: { fontSize:'13px', color:'#2D1752', fontWeight:'600' } },
            lineColor: '#DCD1EC',
            tickColor: '#DCD1EC',
            crosshair: { color:'#F0E9FC', width:20 }
        },
        yAxis: [
            {
                min: 0,
                title: { text:'จำนวนลงทะเบียน (ราย)', style:{ color:'#5B2FA0', fontSize:'13px', fontWeight:'600' } },
                labels: { style:{ color:'#5B2FA0', fontWeight:'600' } },
                gridLineColor: '#EFE9F8'
            },
            {
                min: 0,
                title: { text:'Authen / ซักประวัติ / จ่ายยา (ราย)', style:{ color:'#4A3670', fontSize:'13px', fontWeight:'600' } },
                labels: { style:{ color:'#4A3670', fontWeight:'600' } },
                opposite: true,
                gridLineWidth: 0
            }
        ],
        tooltip: {
            shared: true, useHTML: true,
            backgroundColor: '#fff',
            borderColor: '#C5B5E5',
            borderRadius: 10,
            shadow: true,
            style: { color:'#2D1752', fontSize:'14px' },
            headerFormat: '<div style=\"font-family:Kanit;font-size:14px;font-weight:700;color:#2D1752;margin-bottom:6px\">วันที่ {point.key}</div>',
            pointFormat: '<span style=\"color:{series.color};font-size:16px\">●</span> {series.name}: <b style=\"color:#2D1752\">{point.y} ราย</b><br>'
        },
        plotOptions: {
            column: {
                borderRadius: 6,
                borderWidth: 0,
                groupPadding: 0.12,
                dataLabels: {
                    enabled: true, format: '{point.y}',
                    style: { fontSize:'12px', fontWeight:'700', color:'#2D1752', textOutline:'none' },
                    verticalAlign: 'top', y: -18
                }
            },
            line: {
                lineWidth: 3,
                marker: { radius:6, symbol:'circle', lineWidth:2, lineColor:'#fff' },
                dataLabels: {
                    enabled: true, format: '{point.y}',
                    style: { fontSize:'12px', fontWeight:'700', textOutline:'none' },
                    verticalAlign: 'bottom', y: -6
                }
            }
        },
        legend: {
            itemStyle: { fontFamily:'Sarabun', fontSize:'13px', color:'#2D1752', fontWeight:'600' },
            itemHoverStyle: { color:'#7C4DFF' },
            borderRadius: 8, borderWidth: 1, borderColor: '#E2DAF0',
            backgroundColor: '#FAF7FF',
            padding: 10
        },
        series: [
            {
                type: 'column', name: 'ลงทะเบียนทั้งหมด',
                data: $registers, color: '#7C4DFF', yAxis: 0
            },
            {
                type: 'line', name: 'ขอ Authen',
                data: $authens, color: '#1B6E3F', yAxis: 1,  /* ปรับสีเขียวให้เข้มขึ้นในกราฟ */
                marker: { fillColor:'#1B6E3F' }
            },
            {
                type: 'line', name: 'ซักประวัติ',
                data: $screens, color: '#0B6478', yAxis: 1,  /* ปรับสีฟ้าให้เข้มขึ้นในกราฟ */
                marker: { fillColor:'#0B6478' }, dashStyle: 'ShortDash'
            },
            {
                type: 'line', name: 'จ่ายยา',
                data: $drugs, color: '#A65B00', yAxis: 1,     /* ปรับสีส้มให้เข้มขึ้นในกราฟ */
                marker: { fillColor:'#A65B00' }, dashStyle: 'Dot'
            }
        ]
    });
});
");
?>