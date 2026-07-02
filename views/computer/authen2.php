<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use \miloschuman\highcharts\Highcharts;
use yii\web\JsExpression;
use kartik\grid\GridView;
use kartik\export\ExportMenu;
use yii\helpers\Json;

/* @var $this yii\web\View */

###$this->title = 'ข้อมูลการยืนยันตัวตน จองเคล ปิดสิทธิ์';
?>

<style>
/* ปิด Left Menu และขยายพื้นที่ content */
.sidebar,
.main-sidebar,
.sidebar-menu {
    display: none !important;
}

.content-wrapper, .content {
    margin-left: 0 !important;
}
</style>

<style>
/* ปุ่ม Modern แบบ Gradient มีเงา */
.btn-group-modern {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    justify-content: center;
    margin: 20px 0;
}

.btn-modern {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 24px;
    border: none;
    border-radius: 12px;
    font-size: 16px;
    font-weight: bold;
    text-transform: uppercase;
    text-decoration: none;
    color: #fff;
    background: linear-gradient(145deg, #6a11cb, #2575fc);
    box-shadow: 0px 6px 20px rgba(0, 0, 0, 0.2);
    transition: 0.3s ease;
}

.btn-modern i {
    font-size: 20px;
}

.btn-modern:hover {
    transform: translateY(-5px);
    box-shadow: 0px 10px 25px rgba(0, 0, 0, 0.3);
}

/* ปุ่มอื่นแบบ Gradient */
.btn-samba {
    background: linear-gradient(135deg, #6a11cb, #2575fc);
}
.btn-rep {
    background: linear-gradient(135deg, #db2df7, #edb0f7);
}

/* ===== Dashboard Card Style ===== */
.dashboard-card {
    background: rgba(255, 255, 255, 0.9);
    border-radius: 18px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    padding: 30px;
    margin-bottom: 35px;
    transition: transform 0.3s ease;
}

.dashboard-card:hover {
    transform: translateY(-4px);
}

.dashboard-title {
    font-size: 26px;
    font-weight: 600;
    text-align: center;
    color: #4B0082;
    margin-bottom: 25px;
}

/* ===== ตารางแบบผู้บริหาร ===== */
.table-modern {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    border-radius: 12px;
    overflow: hidden;
    font-size: 15px;
    box-shadow: 0px 3px 15px rgba(0, 0, 0, 0.1);
}

.table-modern th {
    background: linear-gradient(to right, #667eea, #764ba2);
    color: white;
    text-align: center;
    padding: 12px;
}

.table-modern td {
    padding: 10px;
    text-align: center;
}

/* สลับสีแถว */
.table-modern tbody tr:nth-child(even) {
    background-color: #f4f8fb;
}
.table-modern tbody tr:nth-child(odd) {
    background-color: #ffffff;
}

/* Hover effect */
.table-modern tbody tr:hover {
    background-color: #e6f0ff;
    transition: 0.2s ease-in-out;
}
.dashboard-title-gradient {
    display: inline-block;
    background: linear-gradient(135deg, #ff9a9e, #fad0c4);
    padding: 14px 28px;
    font-size: 22px;
    font-weight: bold;
    color: #4B0082;
    border-radius: 12px;
    box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
    text-align: center;
    text-shadow: 1px 1px 1px rgba(255,255,255,0.6);
    border: 1px solid #fff;
}
.table-modern thead th {
    background: linear-gradient(135deg, #ff9a9e, #fad0c4); /* โทนเดียวกับหัวข้อ */
    color: #4B0082;
    font-size: 16px;
    font-weight: bold;
    text-align: center;
    padding: 14px;
    border-top: 1px solid #ffffff;
    border-bottom: 2px solid #ffffff;
}

/* ปรับให้ขอบหัวตารางมนแบบ 3 มิติ */
.table-modern thead tr:first-child th:first-child {
    border-top-left-radius: 12px;
}
.table-modern thead tr:first-child th:last-child {
    border-top-right-radius: 12px;
}

</style>


<div class="container">
   <div class="row justify-content-center">
    <!-- การ์ดที่ 1 -->
    <div class="col-12 col-md-12 col-lg-10">
      <div class="card shadow dashboard-card mb-4">
        <div class="card-header bg-danger text-white d-flex align-items-center">
          <i class="glyphicon glyphicon-list-alt me-2"></i>
          <span class="dashboard-title-gradient">
            ข้อมูลรายวัน ปิดสิทธิ์ ยืนยันตัวตน จองเคลม
          </span>
        </div>
        <div class="card-body">
          <div style="display: none;">
            <?php
            echo \miloschuman\highcharts\Highcharts::widget([
                'scripts' => [
                    'highcharts-more',
                    'highcharts-3d',
                    'modules/drilldown'
                ]
            ]);
            ?>
          </div>

            <?php
            $sql = "SELECT
			DATE_FORMAT(o.reg_datetime, '%Y-%m-%d') AS visit,
			CONCAT(DATE_FORMAT(o.reg_datetime, '%d-%m-'), YEAR(o.reg_datetime) + 543) AS visit_date,
			COUNT(DISTINCT o.visit_id) AS visit_all,
				COUNT(DISTINCT o.VISIT_ID)
					- 
					COUNT(DISTINCT CASE 
						WHEN i.visit_id IS NOT NULL  
						  OR p.natn_id <> '99'   
						THEN o.VISIT_ID 
					END) AS visit_opd,

				COUNT(DISTINCT CASE WHEN i.visit_id IS NOT NULL THEN i.visit_id END) AS visit_ipd,
			COUNT(DISTINCT CASE WHEN c.visit_id IS NOT NULL THEN c.visit_id END) AS visit_close,
			COUNT(DISTINCT CASE WHEN ak.visit_id IS NOT NULL THEN ak.visit_id END) AS visit_authen,
			COUNT(DISTINCT CASE WHEN lc.visit_id IS NOT NULL THEN lc.visit_id END) AS jongclaim,
			COUNT(DISTINCT CASE WHEN c.transaction_id IS NULL OR c.transaction_id = '' THEN c.visit_id END) AS 'ปิดสิทธิ์mbase',
			COUNT(DISTINCT CASE WHEN c.transaction_id IS NOT NULL AND c.transaction_id <> '' THEN c.visit_id END) AS 'ปิดสิทธิ์api',
				COUNT(CASE WHEN c.transaction_id IS NULL AND c.claimtype = '' THEN c.visit_id END) AS 'edc-web'          
		FROM opd_visits o
		INNER JOIN cid_hn ch ON o.HN = ch.HN
		INNER JOIN population p ON ch.CID = p.CID
		INNER JOIN opd_diagnosis od ON od.visit_id = o.visit_id AND dxt_id = 1 AND od.is_cancel = 0
		LEFT JOIN authen_kiosk ak ON ak.visit_id = o.visit_id
		LEFT JOIN close_visits c ON c.visit_id = o.visit_id AND c.is_cancel = 0
		LEFT JOIN ipd_reg i ON i.visit_id = o.visit_id AND i.is_cancel = 0
		LEFT JOIN log_closevisits lc ON lc.visit_id = o.visit_id
		WHERE o.reg_datetime BETWEEN CURDATE() - INTERVAL 1 MONTH AND NOW()
		  AND o.is_cancel = 0
		GROUP BY DATE_FORMAT(o.reg_datetime, '%Y-%m-%d')
		ORDER BY visit DESC;

					";

            $rawData = Yii::$app->db70->createCommand($sql)->queryAll();

            $categories = [];
            $close_data = [];
            $authen_data = [];
            ?>

            <div id="daily_chart" style="width: 100%; height: 400px;"></div>

           <div class="table-responsive">
    <table class="table table-bordered table-striped table-modern">
        <thead>
            <tr style="background-color: #ffe6f0;">
                <th>วัน</th>
                <th>ทั้งหมด</th>
                <th>opd</th>
                <th>ipd</th>
                <th>ยืนยันตัวตน</th>
                <th>จองเคลม</th>
                <th>ปิดสิทธิ์</th>
				<th>ปิดสิทธิ์ edc-web</th>
                <th>สถานะ</th> <!-- ✅ เพิ่มคอลัมน์สถานะ -->
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rawData as $data): ?>
                <?php
                    $visit2 = intval($data['visit_opd']);
                    $authen = intval($data['visit_authen']);
                    $claim = intval($data['jongclaim']);
                    $close = intval($data['visit_close']);

                    $okCount = 0;
                    $okCount += ($authen >= $visit2) ? 1 : 0;
                    $okCount += ($claim >= $visit2) ? 1 : 0;
                    $okCount += ($close >= $visit2) ? 1 : 0;

                    $statusText = $okCount >= 3
                        ? "<span style='color: green; font-weight: bold;'>✅OK</span>"
                        : "<span style='color: red; font-weight: bold;'>NOT OK</span>";
                ?>
               <tr>
    <td style="color: #ed7728;"><?php echo htmlspecialchars($data['visit_date']); ?></td> 

    <td>
        <span style="background-color: #b3e5fc; color: #003c5f; padding: 6px 14px; border-radius: 10px; display: inline-block;">
            <?= intval($data['visit_all']) ?>
        </span>
    </td>

    <td>
        <span style="background-color: #4fc3f7; color: white; padding: 6px 14px; border-radius: 10px; display: inline-block;">
            <?= htmlspecialchars($visit2) ?>
        </span>
    </td>

    <td>
        <span style="background-color: #ffb74d; color: white; padding: 6px 14px; border-radius: 10px; display: inline-block;">
            <?= intval($data['visit_ipd']) ?>
        </span>
    </td>

    <td>
        <span style="background-color: #ff9800; color: white; padding: 6px 14px; border-radius: 10px; display: inline-block;">
            <?= htmlspecialchars($authen) ?>
        </span>
    </td>

    <td>
        <span style="background-color: #ffd54f; color: black; padding: 6px 14px; border-radius: 10px; display: inline-block;">
            <?= htmlspecialchars($claim) ?>
        </span>
    </td>

    <td>
        <span style="background-color: #aed581; color: black; padding: 6px 14px; border-radius: 10px; display: inline-block;">
            <?= htmlspecialchars($close) ?>
        </span>
    </td>

    <td>
        <span style="background-color: #66bb6a; color: white; padding: 6px 14px; border-radius: 10px; display: inline-block;">
            <?= intval($data['edc-web']) ?>
        </span>
    </td>

    <td><?= $statusText ?></td> <!-- ✅ แสดงผลลัพธ์ -->
</tr>

                <?php
                    $categories[] = $data['visit_date'];
                    $close_data[] = floatval($data['visit_close']);
                    $authen_data[] = floatval($data['visit_authen']);
                ?>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>


            <?php
           $this->registerJs("$(function () {
    $('#daily_chart').highcharts({
        colors: ['#CC99FF', '#FFCC00'],
        chart: {
            type: 'column',
            options3d: {
                enabled: true,
                alpha: 15,
                beta: 15,
                depth: 50
            }
        },
        title: {
            text: 'สรุปผลข้อมูลราย 1 เดือน'
        },
        xAxis: {
            categories: " . json_encode($categories) . ",
            title: {
                text: '<b>วัน</b>'
            }
        },
        yAxis: {
            title: {
                text: '<b>เปอร์เซ็นต์</b>'
            },
            max: 100
        },
        plotOptions: {
            column: {
                depth: 25,
                dataLabels: {
                    enabled: true,
                    format: '{point.y:.2f}%',
                    style: {
                        fontWeight: 'bold',
                        color: '#000'
                    }
                }
            }
        },
        series: [{
            name: 'ปิดสิทธิ์',
            data: " . json_encode($close_data) . "
        }, {
            name: 'ยืนยันตัวตน',
            data: " . json_encode($authen_data) . "
        }]
    });
});");
?>
        
               
              </tbody>
            </table>
          </div>

          <!-- หมายเหตุ -->
          <div style="background-color: #E6E6FA; padding: 15px; border-radius: 8px; margin-top: 20px; font-size: 1.1em; color: #4B0082; text-align: center; font-weight: bold;">
            **** การปิดสิทธิ์ จองเคลม ยืนยันตัวตน (เฉพาะคนไทย ไม่นับผู้ป่วยใน) *****
          </div>
        </div>
      </div>
    </div>
		
<div class="col-12 col-md-12 col-lg-10 mt-4">
  <div class="card shadow dashboard-card">
    <div class="card-header bg-success text-white d-flex align-items-center justify-content-between">
      <div>
        <i class="glyphicon glyphicon-folder-open me-2"></i>
        <span class="dashboard-title-gradient">ข้อมูลจากฐาน JHCIS</span>
      </div>
    </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered table-striped table-modern">

        </div>
        <?php
        $sql = "SELECT DISTINCT 
            #DATE_FORMAT(k.visitdate, '%d-%m-%Y') AS regdate,
			CONCAT(DATE_FORMAT(k.visitdate, '%d-%m-'), YEAR(k.visitdate) + 543) AS regdate,
            COUNT(DISTINCT k.seq) AS visit,
			COUNT(DISTINCT k.claimcode_nhso) AS authen,
			COUNT(DISTINCT k.claimcode) AS close,
            SUM(CASE WHEN k.status = '200' THEN 1 ELSE 0 END) AS claim,
            SUM(CASE WHEN k.status != '200' THEN 1 ELSE 0 END) AS noclaim
FROM 
(SELECT 
v.visitdate, v.visitno AS seq, lc.status,
       COALESCE(v.claimcode_nhso, '') AS claimcode_nhso,
       v.hiciauthen_nhso, v.pid, p.idcard AS cid, p.telephoneperson, p.mobile,
       c.rightcode, c.rightname, cl.claimcode,
       CONCAT(p.fname, ' ', lname) AS fullname, 
       TIMESTAMPDIFF(YEAR, p.birth, v.visitdate) AS age,
       REPLACE(IF(cdisease.mapdisease <> '', cdisease.mapdisease, cdisease.diseasecode), '.', '') AS DIAGCODE,
       vd.dxtype
FROM visit v
LEFT JOIN close_visits cl  ON cl.visit_id = v.visitno
LEFT JOIN log_closevisitsj lc ON lc.visit_id = v.visitno
LEFT JOIN person p ON p.pid = v.pid
LEFT JOIN cright c ON c.rightcode = v.rightcode
LEFT JOIN visitdiag vd ON vd.visitno = v.visitno AND vd.dxtype = '01'
LEFT JOIN cdisease ON vd.diagcode = cdisease.diseasecode
WHERE DATE(v.visitdate) BETWEEN DATE_SUB(CURDATE(), INTERVAL 30 DAY) AND CURDATE()) as k
GROUP BY k.visitdate  ORDER BY regdate DESC
       ";

        // ดึงข้อมูลจากฐานข้อมูล
        $result = Yii::$app->db_jhcis->createCommand($sql)->queryAll();

        ?>
<table class="table table-modern">
        <div class="table-responsive" style="margin-top: 20px;">
           <table class="table table-bordered table-striped table-modern">
                <thead>
                  <tr style="background-color: #ffe6f0;">
                        <th>วัน</th>
                        <th>ทั้งหมด</th>
                        <th>Authen</th>                     
                        <th>จองเคลม</th>
                        <th>ไม่จอง</th>
						<th>ปิดสิทธิ์</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($result as $row): ?>
                       <tr>
    <!-- วันที่ สีส้ม -->
    <td style="color: #ed7728;"><?php echo htmlspecialchars($row['regdate']); ?></td> 

    <!-- visit: ฟ้าอ่อน -->
    <td>
        <span style="background-color: #b3e5fc; color: #003c5f; padding: 6px 12px; border-radius: 8px; display: inline-block;">
            <?php echo htmlspecialchars($row['visit']); ?>
        </span>
    </td>

    <!-- authen: ฟ้าเข้ม -->
    <td>
        <span style="background-color: #4fc3f7; color: white; padding: 6px 12px; border-radius: 8px; display: inline-block;">
            <?php echo htmlspecialchars($row['authen']); ?>
        </span>
    </td>

    <!-- claim: เขียวอ่อน -->
    <td>
        <span style="background-color: #aed581; color: #1b5e20; padding: 6px 12px; border-radius: 8px; display: inline-block;">
            <?php echo htmlspecialchars($row['claim']); ?>
        </span>
    </td>

    <!-- noclaim: เหลืองส้ม -->
    <td>
        <span style="background-color: #ffcc80; color: #6d4c41; padding: 6px 12px; border-radius: 8px; display: inline-block;">
            <?php echo htmlspecialchars($row['noclaim']); ?>
        </span>
    </td>

    <!-- close: เขียวเข้ม -->
    <td>
        <span style="background-color: #66bb6a; color: white; padding: 6px 12px; border-radius: 8px; display: inline-block;">
            <?php echo htmlspecialchars($row['close']); ?>
        </span>
    </td>
</tr>

                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
