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

$this->title = 'M30hospital(045489064)';
?>
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
    .btn-samba {
        background: linear-gradient(135deg, #6a11cb, #2575fc);
    }
	.btn-rep {
       background: linear-gradient(135deg, #db2df7, #edb0f7);
	
    }
    /* Hover Effect */
    .btn-modern:hover {
        transform: translateY(-3px);
        box-shadow: 0px 6px 15px rgba(0, 0, 0, 0.3);
    }
</style>

<div class="btn-group-modern">
    
	 
	 <a href="<?= Url::to(['/closeall/index']) ?>" class="btn-modern btn-rep">
        <i class="fa fa-check-square-o"></i>การปิดสิทธิ์ 
    </a>
	 <a href="<?= Url::to(['/closevisit1/index']) ?>" class="btn-modern btn-rep">
        <i class="fa fa-check-square-o"></i>การจองเคลม
    </a>
	
</div>

<div class="panel-body" style="background-color: #E6E6FA; border-radius: 10px; padding: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
    
            <div class="col-md-6">
                <div class="panel panel-xdanger">
                    <div class="panel-heading">
                        <i class="glyphicon glyphicon-list-alt"></i> ข้อมูลแยกตามสิทธิ์การรักษา
                    </div>
                    <div class="panel-body">
                        <div style="display: none">
                            <?php
                            echo Highcharts::widget([
                                'scripts' => [
                                    'highcharts-more',
                                    'highcharts-3d',
                                    'modules/drilldown'
                                ]
                            ]);
                            ?>
                        </div>
                        <div id="monthly_chart" style="width: 100%; height: 400px;"></div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>วัน</th>
                                        <th>สิทธิการรักษา</th>
                                        <th>จำนวนทั้งหมด</th>
                                        <th>จำนวนยืนยันตัวตน</th>
                                        <th>จำนวน Non-Authen</th>
                                    </tr>
                                </thead>
                                <tbody>

                        <?php
                        $sql = "SELECT 
    DATE_FORMAT(b.reg_datetime, '%Y-%m-%d') AS reg_day,
    f.INSCL_NAME AS 'สิทธิการรักษา',
    COUNT(b.VISIT_ID) AS visit,
    COUNT(ak.claimcode) AS authencode,
    COUNT(b.VISIT_ID) - COUNT(ak.claimcode) AS non,
    ROUND(COUNT(ak.claimcode) / COUNT(b.VISIT_ID) * 100, 2) AS percent_authen,
    ROUND((COUNT(b.VISIT_ID) - COUNT(ak.claimcode)) / COUNT(b.VISIT_ID) * 100, 2) AS percent_non_authen
FROM opd_visits b 
INNER JOIN cid_hn c ON b.HN = c.HN
INNER JOIN population p ON c.CID = p.CID
INNER JOIN service_units e ON b.UNIT_REG = e.unit_id
LEFT JOIN authen_kiosk ak ON p.CID = ak.cid AND DATE(b.REG_DATETIME) = DATE(ak.d_update)
LEFT JOIN main_inscls f ON b.INSCL = f.INSCL
LEFT JOIN uc_inscl g ON c.CID = g.CID AND (g.date_abort > DATE(b.REG_DATETIME) OR DAY(g.DATE_ABORT) = 0) AND TRIM(g.hospmain) <> ''
WHERE b.IS_CANCEL = 0
AND b.REG_DATETIME BETWEEN CURDATE() AND NOW()
AND b.VISIT_ID NOT IN (SELECT visit_id FROM mobile_visits)
AND b.UNIT_REG NOT IN ('42')
GROUP BY DATE_FORMAT(b.reg_datetime, '%Y-%m-%d'), f.INSCL_NAME

UNION ALL

SELECT 
    'รวมทั้งหมด' AS reg_day,
    NULL AS 'สิทธิการรักษา',
    SUM(visit) AS visit,
    SUM(authencode) AS authencode,
    SUM(non) AS non,
    NULL AS percent_authen,
    NULL AS percent_non_authen
FROM (
    SELECT 
        COUNT(b.VISIT_ID) AS visit,
        COUNT(ak.claimcode) AS authencode,
        COUNT(b.VISIT_ID) - COUNT(ak.claimcode) AS non
    FROM opd_visits b 
    INNER JOIN cid_hn c ON b.HN = c.HN
    INNER JOIN population p ON c.CID = p.CID
    INNER JOIN service_units e ON b.UNIT_REG = e.unit_id
    LEFT JOIN authen_kiosk ak ON p.CID = ak.cid AND DATE(b.REG_DATETIME) = DATE(ak.d_update)
    LEFT JOIN main_inscls f ON b.INSCL = f.INSCL
    LEFT JOIN uc_inscl g ON c.CID = g.CID AND (g.date_abort > DATE(b.REG_DATETIME) OR DAY(g.DATE_ABORT) = 0) AND TRIM(g.hospmain) <> ''
    WHERE b.IS_CANCEL = 0
    AND b.REG_DATETIME BETWEEN CURDATE() AND NOW()
    AND b.VISIT_ID NOT IN (SELECT visit_id FROM mobile_visits)
    AND b.UNIT_REG NOT IN ('42')
    GROUP BY DATE_FORMAT(b.reg_datetime, '%Y-%m-%d'), f.INSCL_NAME
) as subquery

ORDER BY reg_day ASC, 'สิทธิการรักษา' ASC;";

                        $rawData = Yii::$app->db2->createCommand($sql)->queryAll();

                        $chart_data = [];
                        foreach ($rawData as $data) {
                            echo "<tr>
                                    <td>{$data['reg_day']}</td>
                                    <td>{$data['สิทธิการรักษา']}</td>
                                    <td>" . intval($data['visit']) . "</td>
                                    <td>" . intval($data['authencode']) . "</td>
                                    <td>" . intval($data['non']) . "</td>
                                   
                                  </tr>";

                            $chart_data[] = [
                                'name' => $data['สิทธิการรักษา'] . " ({$data['reg_day']})",
                                'y' => floatval($data['percent_authen']),
                            ];
                        }

                        $chart_json = json_encode($chart_data);
                        ?>
                    </tbody>
                </table>
            </div>

            <?php
            $this->registerJs("$(function () {
                $('#monthly_chart').highcharts({
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
                        text: 'สรุปผลข้อมูลรายวันแยกตามสิทธิ์การรักษา'
                    },
                    xAxis: {
                        categories: " . json_encode(array_column($chart_data, 'name')) . ",
                        title: {
                            text: '<b>สิทธิการรักษา (วัน)</b>'
                        }
                    },
                    yAxis: {
                        title: {
                            text: '<b>เปอร์เซ็นต์</b>'
                        },
                        max: 100
                    },
                    plotOptions: {
                        series: {
                            dataLabels: {
                                enabled: true,
                                format: '{point.y:.2f}%'
                            }
                        }
                    },
                    series: [{
                        name: 'Authen',
                        data: " . $chart_json . "
                    }]
                });
            });");
            ?>
        </div>
    </div>
</div>

			  
	<div class="col-md-6">
    <div class="panel panel-danger">
        <div class="panel-heading">
            <i class="glyphicon glyphicon-list-alt"></i> ข้อมูลรายวัน ปิดสิทธิ์ ยืนยันตัวตน จองเคลม
        </div>
        <div class="panel-body">
            <div style="display: none">
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
						DATE_FORMAT(b.reg_datetime, '%Y-%m-%d') AS reg_day,

						-- visit ทั้งหมดไม่ซ้ำ
						COUNT(DISTINCT b.VISIT_ID) AS visit,

						-- รวม visit_id ที่เข้าเงื่อนไข ต่างชาติ OR HD OR IPD ไม่ซ้ำ
						COUNT(DISTINCT CASE 
							WHEN p.natn_id <> '99' 
							  OR b.unit_reg IN ('42') 
							  OR i.visit_id IS NOT NULL 
							THEN b.VISIT_ID 
						END) AS ipd_hd_foreigns,

						-- visit2 = visit - ipd_hd_foreigns
						COUNT(DISTINCT b.VISIT_ID) -
						COUNT(DISTINCT CASE 
							WHEN p.natn_id <> '99' 
							  OR b.unit_reg IN ('42') 
							  OR i.visit_id IS NOT NULL 
							THEN b.VISIT_ID 
						END) AS visit2,

						-- รายการอื่น ๆ
						COUNT(DISTINCT CASE WHEN p.natn_id <> '99' THEN b.VISIT_ID END) AS foreigns,
						COUNT(DISTINCT CASE WHEN b.unit_reg IN ('42') THEN b.VISIT_ID END) AS hd,
						COUNT(DISTINCT i.adm_id) AS ipd,

						COUNT(DISTINCT CASE WHEN ak.claimcode <> '' THEN b.VISIT_ID END) AS authen,
						COUNT(DISTINCT CASE 
						WHEN cl.claimcode IS NOT NULL AND cl.claimcode <> '' THEN b.VISIT_ID 
						END) AS close,
						COUNT(DISTINCT CASE WHEN lc.status = '200' THEN b.VISIT_ID END) AS claim,
						COUNT(DISTINCT CASE WHEN lc.status <> '200' THEN b.VISIT_ID END) AS nonclaim,

						COUNT(DISTINCT b.VISIT_ID) - COUNT(DISTINCT ak.claimcode) AS non,
						ROUND(COUNT(DISTINCT ak.claimcode) / COUNT(DISTINCT b.VISIT_ID) * 100, 2) AS percent_authen,
						ROUND(COUNT(DISTINCT cl.claimcode) / COUNT(DISTINCT b.VISIT_ID) * 100, 2) AS percent_close,
						ROUND((COUNT(DISTINCT b.VISIT_ID) - COUNT(DISTINCT ak.claimcode)) / COUNT(DISTINCT b.VISIT_ID) * 100, 2) AS nonx

					FROM opd_visits b
					INNER JOIN cid_hn c ON b.HN = c.HN
					INNER JOIN population p ON c.CID = p.CID
					INNER JOIN service_units e ON b.UNIT_REG = e.unit_id
					LEFT JOIN authen_kiosk ak ON p.CID = ak.cid AND DATE(b.REG_DATETIME) = DATE(ak.d_update)
					LEFT JOIN log_closevisits lc ON lc.visit_id = b.visit_id
					LEFT JOIN ipd_reg i ON i.visit_id = b.visit_id AND i.is_cancel = 0
					LEFT JOIN close_visits cl ON cl.visit_id = b.visit_id

					WHERE 
						b.IS_CANCEL = 0
						AND b.REG_DATETIME BETWEEN CURDATE() - INTERVAL 5 DAY AND NOW()

					GROUP BY 
						DATE_FORMAT(b.reg_datetime, '%Y-%m-%d')
					ORDER BY 
						reg_day DESC;
					";

            $rawData = Yii::$app->db2->createCommand($sql)->queryAll();

            $categories = [];
            $close_data = [];
            $authen_data = [];
            ?>

            <div id="daily_chart" style="width: 100%; height: 400px;"></div>

            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
					<tr style="background-color: #ffe6f0;">
					<th>วัน</th>
					<th>ทั้งหมด</th>
					<th>เข้าเงื่อนไข</th>
					<th>ผู้ป่วยใน</th>
					<th>ต่างชาติ</th>
					<th>hd</th>
					<th>ยืนยันตัวตน</th>
					<th>จองเคลม</th>
					<th>ปิดสิทธิ์</th>
				</tr>

                    </thead>
                    <tbody>
                        <?php foreach ($rawData as $data): ?>
                            <tr>
                                <td><?= $data['reg_day'] ?></td>
                                <td><?= intval($data['visit']) ?></td>
								 <td><?= intval($data['visit2']) ?></td>
								  <td><?= intval($data['ipd']) ?></td>
								   <td><?= intval($data['foreigns']) ?></td>
								    <td><?= intval($data['hd']) ?></td>
                                <td><?= intval($data['authen']) ?></td>
                                <td><?= intval($data['claim']) ?></td>
                                <td><?= intval($data['close']) ?></td>
								
                            </tr>
                            <?php
                            $categories[] = $data['reg_day'];
                            $close_data[] = floatval($data['percent_close']);
                            $authen_data[] = floatval($data['percent_authen']);
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
            text: 'สรุปผลข้อมูลราย 5 วันย้อนหลัง'
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
        </div>
   


       <!-- ส่วนที่ต้องการเพิ่มพื้นหลัง -->
       <div style="background-color: #E6E6FA; padding: 15px; border-radius: 8px; margin-top: 20px; font-size: 1.2em; color: #4B0082; text-align: center; font-weight: bold;">
            **** ตัดข้อมูลผู้ป่วยใน เอามาทุกแผนกรวมทั้ง Mobile_visits *****
        </div>

        <?php
        $sql = "SELECT DISTINCT 
            DATE(k.visitdate) AS regdate, 
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
WHERE DATE(v.visitdate) BETWEEN DATE_SUB(CURDATE(), INTERVAL 5 DAY) AND CURDATE()) as k
GROUP BY k.visitdate  ORDER BY regdate DESC
       ";

        // ดึงข้อมูลจากฐานข้อมูล
        $result = Yii::$app->db_jhcis->createCommand($sql)->queryAll();

        ?>

        <div class="table-responsive" style="margin-top: 20px;">
            <table class="table table-striped table-bordered">
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
                            <td><?php echo htmlspecialchars($row['regdate']); ?></td>
                            <td><?php echo htmlspecialchars($row['visit']); ?></td>
                            <td><?php echo htmlspecialchars($row['authen']); ?></td>
                           
                            <td><?php echo htmlspecialchars($row['claim']); ?></td>
                            <td><?php echo htmlspecialchars($row['noclaim']); ?></td>
							<td><?php echo htmlspecialchars($row['close']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
