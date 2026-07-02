<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use \miloschuman\highcharts\Highcharts;
use yii\web\JsExpression;
use kartik\grid\GridView;
use kartik\export\ExportMenu;
use yii\data\ArrayDataProvider;

/* @var $this yii\web\View */

$this->title = 'M30hospital(045489064)';
?>

<script src="https://code.highcharts.com/highcharts.js"></script>
<div class="col-md-6">
    <div class="panel panel-primary">
        <div class="panel-heading" style="background: linear-gradient(90deg, #CC99FF, #993366, #FF0000, #FFCC00, #9933FF, #000080); color: #ffffff;">
            <i class="glyphicon glyphicon-list-alt"></i> ข้อมูลทันตกรรมพระราชทาน โรงพยาบาลม่วงสามสิบ
        </div>
        <div class="panel-body">
            <!-- ตารางข้อมูล -->
            <div class="table-responsive">
                <?php
                $sql = "SELECT 
                    DATE(k.REG_DATETIME) AS regdate,
                    COUNT(k.visit_id) AS register,
                    SUM(CASE WHEN k.claimcode <> '' THEN 1 ELSE 0 END) AS authen,
                    SUM(CASE WHEN ISNULL(k.claimcode) OR k.claimcode = '' THEN 1 ELSE 0 END) AS no_authen,
                    SUM(CASE WHEN k.drug_id IS NOT NULL THEN 1 ELSE 0 END) AS drug
                FROM 
                    (SELECT 
                         o.REG_DATETIME, 
                         o.visit_id, 
                         o.hn, 
                         o.unit_reg, 
                         u.unit_name, 
                         a.claimcode,
                         d.drug_id, 
                         i.nickname 
                     FROM  
                         opd_visits o
                     LEFT JOIN service_units u ON u.unit_id = o.unit_reg
                     LEFT JOIN authen_kiosk a ON a.visit_id = o.visit_id
                     LEFT JOIN prescriptions ps ON ps.visit_id = o.visit_id AND ps.is_cancel = 0
                     LEFT JOIN drugs d ON d.drug_id = ps.drug_id
                     LEFT JOIN opd_operations op ON op.visit_id = o.visit_id AND op.is_cancel = 0
                     LEFT JOIN icd9cm i ON i.icd9 = op.icd9
                     WHERE 
                         o.unit_reg = '73'  
                         AND o.is_cancel = 0
                         AND o.REG_DATETIME BETWEEN '2024-11-27 00:01' AND '2024-11-28 23:59'
                     GROUP BY 
                         o.visit_id  
                    ) AS k
                GROUP BY 
                    DATE(k.REG_DATETIME)";
                $rawData = Yii::$app->db14->createCommand($sql)->queryAll();
                ?>

                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>วันเดือนปี</th>
                            <th>ลงทะเบียนทั้งหมด</th>
                            <th>ขอ Authen</th>
                            <th>ไม่ขอ Authen</th>
                            <th>จ่ายยา</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rawData as $data): ?>
                            <tr>
                                <td><?php echo '' . $data['regdate']; ?></td>
                                <td><?php echo intval($data['register']); ?></td>
                                <td><?php echo intval($data['authen']); ?></td>
                                <td><?php echo intval($data['no_authen']); ?></td>
                                <td><?php echo intval($data['drug']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- กราฟ -->
            <div id="chart-container" style="width: 100%; height: 400px;"></div>
        </div>
    </div>
</div>

<?php
$this->registerJs("
    $(function () {
        Highcharts.chart('chart-container', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'สถิติการลงทะเบียนและบริการ (27-28 พฤศจิกายน 2024)'
            },
            xAxis: {
                categories: " . json_encode(array_column($rawData, 'regdate')) . ",
                title: {
                    text: 'วันที่'
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'จำนวน'
                }
            },
            tooltip: {
                shared: true,
                useHTML: true,
                headerFormat: '<b>{point.key}</b><br>',
                pointFormat: '<span style=\"color:{series.color}\">{series.name}</span>: <b>{point.y}</b><br/>'
            },
            plotOptions: {
                column: {
                    dataLabels: {
                        enabled: true,
                        format: '{point.y}', // แสดงตัวเลขบนกราฟ
                        style: {
                            fontSize: '12px',
                            fontWeight: 'bold',
                            color: '#333333'
                        }
                    }
                }
            },
            series: [{
                name: 'ลงทะเบียนทั้งหมด',
                data: " . json_encode(array_map('intval', array_column($rawData, 'register'))) . ",
                color: '#CC99FF'
            }, {
                name: 'ขอ Authen',
                data: " . json_encode(array_map('intval', array_column($rawData, 'authen'))) . ",
                color: '#993366'
            }, {
                name: 'ไม่ขอ Authen',
                data: " . json_encode(array_map('intval', array_column($rawData, 'no_authen'))) . ",
                color: '#FF0000'
            }, {
                name: 'จ่ายยา',
                data: " . json_encode(array_map('intval', array_column($rawData, 'drug'))) . ",
                color: '#FFCC00'
            }]
        });
    });
");
?>

