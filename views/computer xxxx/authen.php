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
    
	 <a href="<?= Url::to(['/computer/authen']) ?>" class="btn-modern btn-rep">
        <i class="fa fa-check-square-o"></i> รายวัน-สัปดาห์
    </a>
	 <a href="<?= Url::to(['/rptfdh/reportall']) ?>" class="btn-modern btn-rep">
        <i class="fa fa-check-square-o"></i>ผู้ป่วยนอกรายเดือน
    </a>
	 <a href="<?= Url::to(['/rptfdh/reportipd']) ?>" class="btn-modern btn-rep">
        <i class="fa fa-check-square-o"></i>ผู้ป่วยในรายเดือน
    </a>
	<a href="<?= Url::to(['/rptfdh/rep']) ?>" class="btn-modern btn-rep">
        <i class="fa fa-check-square-o"></i>ตรวจนำเข้า REP
    </a>
	<a href="<?= Url::to(['/ipuc/index']) ?>" class="btn-modern btn-rep">
        <i class="fa fa-check-square-o"></i>ลูกหนี้หลังรับ STM แบบรายตัว
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
                                        <th>จำนวน Authen</th>
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
    <div class="panel panel-ปdanger">
        <div class="panel-heading">
            <i class="glyphicon glyphicon-list-alt"></i> ข้อมูลรายวัน Authen Code
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
            <div id="daily_chart" style="width: 100%; height: 400px;"></div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                               <th>วัน</th>
                            <th>ทั้งหมด</th>
                            <th>Authen</th>
                            <th>No-Auth</th>
                            <th>จองเคลม</th>
                            <th>ไม่จอง</th>
							<th>ปิดสิทธิ์</th>
                           
                           
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT 
                        DATE_FORMAT(b.reg_datetime, '%Y-%m-%d') AS reg_day,
                        COUNT(DISTINCT b.VISIT_ID) AS visit,   -- นับเฉพาะ visit_id ที่ไม่ซ้ำ
                        COUNT(DISTINCT ak.claimcode) AS authencode,   -- นับเฉพาะ claimcode ที่ไม่ซ้ำ
						COUNT(DISTINCT cl.claimcode) AS close,
                        COUNT(DISTINCT CASE WHEN lc.status = '200' THEN b.VISIT_ID END) AS claim,
                            COUNT(DISTINCT CASE WHEN lc.status <> '200' THEN b.VISIT_ID END) AS nonclaim,-- นับ claim เฉพาะ visit_id ที่ status = 200
                        COUNT(DISTINCT b.VISIT_ID) - COUNT(DISTINCT ak.claimcode) AS non,
                        ROUND(COUNT(DISTINCT ak.claimcode) / COUNT(DISTINCT b.VISIT_ID) * 100, 2) AS percent_authen,
                        ROUND((COUNT(DISTINCT b.VISIT_ID) - COUNT(DISTINCT ak.claimcode)) / COUNT(DISTINCT b.VISIT_ID) * 100, 2) AS nonx
                    FROM opd_visits b
                    INNER JOIN cid_hn c ON b.HN = c.HN
                    INNER JOIN population p ON c.CID = p.CID
                    INNER JOIN service_units e ON b.UNIT_REG = e.unit_id
                    LEFT JOIN authen_kiosk ak ON p.CID = ak.cid AND DATE(b.REG_DATETIME) = DATE(ak.d_update)
                    LEFT JOIN log_closevisits lc ON lc.visit_id = b.visit_id
					LEFT JOIN close_visits cl ON cl.visit_id = b.visit_id		
                    WHERE b.IS_CANCEL = 0
                    AND b.REG_DATETIME BETWEEN CURDATE() - INTERVAL 5 DAY AND NOW()
                   # AND b.VISIT_ID NOT IN (SELECT visit_id FROM ipd_reg)
                    GROUP BY DATE_FORMAT(b.reg_datetime, '%Y-%m-%d')
                    ORDER BY reg_day DESC";

                    
                        $rawData = Yii::$app->db2->createCommand($sql)->queryAll();

                        $chart_data = [];
                        foreach ($rawData as $data) {
                            echo "<tr>
                                    <td>{$data['reg_day']}</td>
                                    <td>" . intval($data['visit']) . "</td>
                                    <td>" . intval($data['authencode']) . "</td>
                                    <td>" . intval($data['non']) . "</td>
                                    <td>" . intval($data['claim']) . "</td>
                                    <td>" . intval($data['nonclaim']) . "</td>
									  <td>" . intval($data['close']) . "</td>
                                    
                                  </tr>";

                            $chart_data[] = [
                                'name' => $data['reg_day'],
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
                        text: 'สรุปผลข้อมูลรายวัน'
                    },
                    xAxis: {
                        categories: " . json_encode(array_column($chart_data, 'name')) . ",
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
(SELECT v.visitdate, v.visitno AS seq, lc.status,
            COALESCE(v.claimcode_nhso, '') AS claimcode_nhso,
            v.hiciauthen_nhso, v.pid, p.idcard AS cid, p.telephoneperson, p.mobile,
            c.rightcode, c.rightname, cl.claimcode,
            CONCAT(p.fname, ' ', lname) AS fullname, 
            TIMESTAMPDIFF(YEAR, p.birth, v.visitdate) AS age,
            REPLACE(IF(cdisease.mapdisease <> '', cdisease.mapdisease, cdisease.diseasecode), '.', '') AS DIAGCODE,
            vd.dxtype
    FROM visit v
    LEFT JOIN person p ON p.pid = v.pid
    LEFT JOIN cright c ON c.rightcode = v.rightcode
    LEFT JOIN visitdiag vd ON vd.visitno = v.visitno AND vd.dxtype = '01'
    LEFT JOIN cdisease ON vd.diagcode = cdisease.diseasecode
    LEFT JOIN log_closevisitsj lc ON lc.visit_id = v.visitno
	LEFT JOIN close_visits cl ON cl.visit_id = v.visitno
    WHERE DATE(v.visitdate) BETWEEN DATE_SUB(CURDATE(), INTERVAL 5 DAY) AND CURDATE()) as k
GROUP BY k.visitdate  ORDER BY regdate DESC
       ";

        // ดึงข้อมูลจากฐานข้อมูล
        $result = Yii::$app->db_jhcis->createCommand($sql)->queryAll();

        ?>

        <div class="table-responsive" style="margin-top: 20px;">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
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
<div class="col-md-12">
    <div class="panel panel-xdanger">
        <div class="panel-heading" style="background: linear-gradient(90deg, #8BC34A, #64B5F6, #9933FF); color: #ffffff;">
            <i class="glyphicon glyphicon-list-alt"></i> ข้อมูลการส่งข้อมูลเคลมผู้ป่วยนอกและผู้ป่วยใน
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
            <div id="monthly_opd_ipd" style="width: 100%; height: 400px;"></div>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>วันที่</th>
                            <th>OPD</th>
                            <th>IPD</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Query to get data
                        $sql = "SELECT 
                            DATE(d_update) AS datesend,
                            SUM(CASE WHEN type = 'OPD' THEN count ELSE 0 END) AS opd_total,
                            SUM(CASE WHEN type = 'IPD' THEN count ELSE 0 END) AS ipd_total
                        FROM (
                            SELECT 
                                d_update,
                                'OPD' AS type,
                                COUNT(*) AS count
                            FROM log_fdh_opd_ck
                            WHERE d_update BETWEEN CURDATE() - INTERVAL 15 DAY AND NOW() AND  messages <> ''
                            GROUP BY DATE(d_update)
                            UNION ALL
                            SELECT 
                                d_update,
                                'IPD' AS type,
                                COUNT(*) AS count
                            FROM log_fdh_ipd_ck
                            WHERE d_update BETWEEN CURDATE() - INTERVAL 15 DAY AND NOW() AND  messages <> ''
                            GROUP BY DATE(d_update)
                        ) AS log
                        GROUP BY DATE(d_update)
                        ORDER BY DATE(d_update) DESC";
                        
                        $rawData = Yii::$app->db2->createCommand($sql)->queryAll();

                        // Prepare data for Highcharts
                        $datesend = [];
                        $opd_totals = [];
                        $ipd_totals = [];

                        foreach ($rawData as $row) {
                            $datesend[] = $row['datesend'];
                            $opd_totals[] = intval($row['opd_total']);
                            $ipd_totals[] = intval($row['ipd_total']);

                            // Display data in table
                            echo "<tr>
                                <td>{$row['datesend']}</td>
                                <td>{$row['opd_total']}</td>
                                <td>{$row['ipd_total']}</td>
                            </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
// Register JavaScript to render Highcharts
$this->registerJs("
    Highcharts.chart('monthly_opd_ipd', {
        chart: {
            type: 'line',
            backgroundColor: null
        },
        title: {
            text: 'จำนวนการส่งเคลมแยกตามวัน'
        },
        xAxis: {
            categories: " . Json::encode($datesend) . ",
            labels: {
                style: {
                    color: '#333333',
                    fontWeight: 'bold'
                }
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: 'จำนวนเคลม',
                style: {
                    color: '#333333',
                    fontWeight: 'bold'
                }
            },
            labels: {
                style: {
                    color: '#333333'
                }
            },
            gridLineColor: '#e6e6e6'
        },
        series: [{
            name: 'OPD',
            data: " . Json::encode($opd_totals) . ",
            color: '#5bc0de',
            dataLabels: {
                enabled: true,
                style: {
                    fontWeight: 'bold',
                    color: '#333333'
                }
            }
        }, {
            name: 'IPD',
            data: " . Json::encode($ipd_totals) . ",
            color: '#f39c12',
            dataLabels: {
                enabled: true,
                style: {
                    fontWeight: 'bold',
                    color: '#333333'
                }
            }
        }],
        plotOptions: {
            line: {
                dataLabels: {
                    enabled: true,
                    style: {
                        color: '#000000'
                    }
                },
                enableMouseTracking: true
            }
        },
        credits: {
            enabled: false
        }
    });
");
?>

<!--#################################################################-->

<div class="col-md-12">
    <div class="panel panel-xdanger">
        <div class="panel-heading" style="background: linear-gradient(90deg, #8BC34A, #64B5F6, #9933FF); color: #ffffff;">
            <i class="glyphicon glyphicon-list-alt"></i> ข้อมูลการส่งข้อมูลเคลมผู้ป่วยนอก FDH แยกตามวัน
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
            <div id="monthly_opd" style="width: 100%; height: 400px;"></div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                         <tr style="background: linear-gradient(90deg, #FFDEE9, #B5FFFC); color: #333333; font-weight: bold;">
                            <th>วันที่ส่งข้อมูล</th>
                            <th>hurb</th>
                            <th>telemed</th>
                            <th>anc</th>
                            <th>ancdent</th>
                            <th>ancdus</th>
                            <th>adtusl1</th>
                            <th>ancus</th>
                            <th>dent</th>
                            <th>janc</th>
                            <th>jcare</th>
                            <th>lab1</th>
                            <th>lab2</th>
                            <th>ฝังยา</th>
                            <th>opstru</th>
                            <th>rider</th>
                            <th>paltive</th>
                            <th>dlab1</th>
                            <th>dlab2</th>
							<th>opae</th>
							<th>stemi</th>
							<th>clopi</th>
							<th>win</th>
							<th>wout</th>
							<th>ม8</th>
							<th>ทันที</th>
							<th>upt</th>
                            <th>total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT 
                            DATE(log.d_update) AS datesend,
                            COUNT(CASE WHEN log.users = 'hurb' THEN 1 END) AS 'hurb',
                            COUNT(CASE WHEN log.users = 'telemed' THEN 1 END) AS 'telemed',
                            COUNT(CASE WHEN log.users = 'anc' THEN 1 END) AS 'anc',
                            COUNT(CASE WHEN log.users = 'ancdent' THEN 1 END) AS 'ancdent',
                            COUNT(CASE WHEN log.users = 'ancdentus' THEN 1 END) AS 'ancdentus',
                            COUNT(CASE WHEN log.users = 'ancdentuslab1' THEN 1 END) AS 'ancdentuslab1',
                            COUNT(CASE WHEN log.users = 'ancus' THEN 1 END) AS 'ancus',
                            COUNT(CASE WHEN log.users = 'dent' THEN 1 END) AS 'dent',
                            COUNT(CASE WHEN log.users = 'janc' THEN 1 END) AS 'janc',
                            COUNT(CASE WHEN log.users = 'janccare' THEN 1 END) AS 'janccare',
                            COUNT(CASE WHEN log.users = 'lab1' THEN 1 END) AS 'lab1',
                            COUNT(CASE WHEN log.users = 'lab2' THEN 1 END) AS 'lab2',
                            COUNT(CASE WHEN log.users = 'oppills' THEN 1 END) AS 'oppills',
                            COUNT(CASE WHEN log.users = 'opstru' THEN 1 END) AS 'opstru',
                            COUNT(CASE WHEN log.users = 'rider' THEN 1 END) AS 'rider',
                            COUNT(CASE WHEN log.users = 'palliative' THEN 1 END) AS 'palliative',
                            COUNT(CASE WHEN log.users = 'dentlab1' THEN 1 END) AS 'dentlab1',
                            COUNT(CASE WHEN log.users = 'dentlab2' THEN 1 END) AS 'dentlab2',
							COUNT(CASE WHEN log.users = 'opae' THEN 1 END) AS 'opae',
							COUNT(CASE WHEN log.users = 'stemi' THEN 1 END) AS 'stemi',
							COUNT(CASE WHEN log.users = 'clopi' THEN 1 END) AS 'clopi',
							COUNT(CASE WHEN log.users = 'walkinin' THEN 1 END) AS 'walkinin',
							COUNT(CASE WHEN log.users = 'walkinout' THEN 1 END) AS 'walkinout',
							COUNT(CASE WHEN log.users = 'opsec8' THEN 1 END) AS 'opsec8',
							COUNT(CASE WHEN log.users = 'oparise' THEN 1 END) AS 'oparise',
							COUNT(CASE WHEN log.users = 'upt' THEN 1 END) AS 'upt',
                            COUNT(*) AS 'total'
                        FROM 
                            log_fdh_opd_ck AS log
                        WHERE 
                            log.d_update BETWEEN CURDATE() - INTERVAL 10 DAY AND NOW() AND  log.messages <> ''
                        GROUP BY 
                            DATE(log.d_update)
                        ORDER BY 
                            DATE(log.d_update) DESC";

                        $rawData = Yii::$app->db2->createCommand($sql)->queryAll();

                        

                        // เตรียมข้อมูลสำหรับ Highcharts
                        $datesend = [];
                        $total = [];

                        foreach ($rawData as $row) {
                            $datesend[] = $row['datesend'];
                            $total[] = intval($row['total']); // แปลงค่าเป็นจำนวนเต็มเพื่อป้องกันปัญหา

                            // แสดงผลข้อมูลในตาราง
                            echo "<tr>
                                <td>{$row['datesend']}</td>
                                <td>{$row['hurb']}</td>
                                <td>{$row['telemed']}</td>
                                <td>{$row['anc']}</td>
                                <td>{$row['ancdent']}</td>
                                <td>{$row['ancdentus']}</td>
                                <td>{$row['ancdentuslab1']}</td>
                                <td>{$row['ancus']}</td>
                                <td>{$row['dent']}</td>
                                <td>{$row['janc']}</td>
                                <td>{$row['janccare']}</td>
                                <td>{$row['lab1']}</td>
                                <td>{$row['lab2']}</td>
                                <td>{$row['oppills']}</td>
                                <td>{$row['opstru']}</td>
                                <td>{$row['rider']}</td>
                                <td>{$row['palliative']}</td>
                                <td>{$row['dentlab1']}</td>
                                <td>{$row['dentlab2']}</td>
								<td>{$row['opae']}</td>
								<td>{$row['stemi']}</td>
								<td>{$row['clopi']}</td>
								<td>{$row['walkinin']}</td>
								<td>{$row['walkinout']}</td>
								<td>{$row['opsec8']}</td>
								<td>{$row['oparise']}</td>
								<td>{$row['upt']}</td>
								
                                <td>{$row['total']}</td>
                            </tr>";
                        }

                        // สร้างกราฟ Highcharts สำหรับข้อมูลรวม
                        $this->registerJs("
                            Highcharts.chart('monthly_opd', {
                                chart: {
                                    type: 'column',
                                    backgroundColor: null,
                                    options3d: {
                                        enabled: true,
                                        alpha: 10,
                                        beta: 10,
                                        depth: 50,
                                        viewDistance: 25
                                    }
                                },
                                title: {
                                    text: 'จำนวนการส่งเคลมแยกตามวัน'
                                },
                                xAxis: {
                                    categories: " . Json::encode($datesend) . ",
                                    labels: {
                                        style: {
                                            color: '#333333',
                                            fontWeight: 'bold'
                                        }
                                    }
                                },
                                yAxis: {
                                    min: 0,
                                    title: {
                                        text: 'จำนวนเคลม',
                                        style: {
                                            color: '#333333',
                                            fontWeight: 'bold'
                                        }
                                    },
                                    labels: {
                                        style: {
                                            color: '#333333'
                                        }
                                    },
                                    gridLineColor: '#e6e6e6'
                                },
                                series: [{
                                    name: 'รวมทั้งหมด',
                                    data: " . Json::encode($total) . ",
                                    color: '#5bc0de',
                                    dataLabels: {
                                        enabled: true,
                                        style: {
                                            fontWeight: 'bold',
                                            color: '#333333'
                                        }
                                    }
                                }],
                                plotOptions: {
                                    column: {
                                        depth: 25,
                                        dataLabels: {
                                            enabled: true,
                                            style: {
                                                color: '#000000'
                                            }
                                        }
                                    }
                                },
                                credits: {
                                    enabled: false
                                }
                            });
                        ");
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<!--###############################################################-->

<div class="col-md-12">
    <div class="panel panel-xdanger">
        <div class="panel-heading">
            <i class="glyphicon glyphicon-list-alt"></i> ข้อมูลการส่งข้อมูลเคลมผู้ป่วยใน FDH แยกตามวัน IPD
        </div>
        <div class="panel-body">
            <div id="monthly_ipd" style="width: 100%; height: 400px;"></div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr style="background: linear-gradient(90deg, #FFDEE9, #B5FFFC); color: #333333; font-weight: bold;">
                            <th>วันที่ส่งข้อมูล</th>
                            <th>ipnormal</th>
                            <th>homeward</th>
                            <th>ฝังยาคุม</th>
							<th>ipae</th>
							<th>stp</th>
							<th>referin</th>
							<th>aereferin</th>
                            <th>total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT 
                        DATE(log.d_update) AS datesend,
                        COUNT(CASE WHEN log.users = 'ipnormal' THEN 1 END) AS 'ipnormal',
                        COUNT(CASE WHEN log.users = 'homeward' THEN 1 END) AS 'homeward',
                        COUNT(CASE WHEN log.users = 'ippills' THEN 1 END) AS 'ippills',
						COUNT(CASE WHEN log.users = 'ipae' THEN 1 END) AS 'ipae',
						COUNT(CASE WHEN log.users = 'stp' THEN 1 END) AS 'stp',
						COUNT(CASE WHEN log.users = 'ipreferin' THEN 1 END) AS 'ipreferin',
						COUNT(CASE WHEN log.users = 'ipaereferin' THEN 1 END) AS 'ipaereferin',
                        COUNT(*) AS 'total'
                        FROM 
                            log_fdh_ipd_ck AS log
                        WHERE 
                            log.d_update BETWEEN CURDATE() - INTERVAL 15 DAY AND NOW()
                        GROUP BY 
                            DATE(log.d_update)
                        ORDER BY 
                            DATE(log.d_update) DESC;";

                        $rawData = Yii::$app->db2->createCommand($sql)->queryAll();

                        $datesend = [];
                        $total = [];

                        foreach ($rawData as $row) {
                            $datesend[] = $row['datesend'];
                            $total[] = (int)$row['total']; // แปลงค่าเป็นตัวเลข
                            echo "<tr>
                                <td>{$row['datesend']}</td>
                                <td>{$row['ipnormal']}</td>
                                <td>{$row['homeward']}</td>
                                <td>{$row['ippills']}</td>
								<td>{$row['ipae']}</td>
								<td>{$row['stp']}</td>
								<td>{$row['ipreferin']}</td>
								<td>{$row['ipaereferin']}</td>
                                <td>{$row['total']}</td>
                            </tr>";
                        }

                       $this->registerJs("
    Highcharts.chart('monthly_ipd', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'จำนวนการส่งเคลม IPD'
        },
        xAxis: {
            categories: " . Json::encode($datesend) . "
        },
        yAxis: {
            min: 0,
            title: {
                text: 'จำนวนเคลม'
            }
        },
        plotOptions: {
            column: {
                dataLabels: {
                    enabled: true,
                    format: '{point.y:,.0f}' // รูปแบบการแสดงตัวเลข
                }
            }
        },
        series: [{
            name: 'รวมทั้งหมด',
            data: " . Json::encode($total) . ",
            colorByPoint: true // เพื่อให้แต่ละแท่งสีต่างกัน
        }],
        credits: {
            enabled: false // ลบเครดิต Highcharts ที่มุมล่างขวา
        }
    });
");

                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

