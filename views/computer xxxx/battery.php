<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use \miloschuman\highcharts\Highcharts;
use yii\web\JsExpression;
use kartik\grid\GridView;
use kartik\export\ExportMenu;

/* @var $this yii\web\View */

$this->title = 'M30hospital(045489064)';
?>

<div class="panel-body">
    <div class="panel panel-warning">
        <div class="panel-heading"><i class="glyphicon glyphicon-plus-sign"></i> ระบบรายงานข้อมูลคอมพิวเตอร์และอุปกรณ์ต่อพ่วง</<i></div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="panel panel-info">
                        <div class="panel-heading"><i class="glyphicon glyphicon-user"></i> การจัดซื้อใหม่อุปกรณ์ประเภทแบตเตอรี่</<i></div>
                        <div class="panel-body">
                            <div>
                            
                            <div id="chart_topopd">
                            </div>
                             <?php
                            $sql = "SELECT 
    fiscal,
    SUM(TOTAL) AS total_sum
FROM 
    (
        SELECT DISTINCT 
            c.EXP_ID,
            c.IVS_DATE,
            a.IVT_ID,
            b.IVT_NAME,
            b.IVC_ID,
            a.QUANTITY,
            d.UUNIT_NAME, 
            ROUND(a.PACK_PRICE, 2) AS PACK_PRICE,
            ROUND(a.QUANTITY * a.PACK_PRICE, 2) AS TOTAL,
            IF(MONTH(c.IVS_DATE) > 9, YEAR(c.IVS_DATE) + 544, YEAR(c.IVS_DATE) + 543) AS fiscal
        FROM 
            order_details a
        JOIN 
            inventory b ON a.IVT_ID = b.IVT_ID
        JOIN 
            invoices c ON a.IVS_ID = c.IVS_ID
        JOIN 
            ivt_units d ON a.UUNIT_ID = d.UUNIT_ID
        WHERE 
            c.IVS_DATE >= '2019-10-01 00:00'
            AND b.IVT_NAME LIKE '%แบต%'
            AND b.IVC_ID = '04'
    ) AS k
GROUP BY 
    fiscal
ORDER BY 
    fiscal;
";
                            $rawData = Yii::$app->db14->createCommand($sql)->queryAll();
                            $main_data = [];
                            foreach ($rawData as $data) {
                                $main_data[] = [
                                    'name' => $data['fiscal'],
                                    'y' => $data['total_sum'] * 1,
                                ];
                                $main = json_encode($main_data);
                                ?>

                                <?php
                                $this->registerJs("$(function () {
                        $('#chart_topopd').highcharts({
                             colors: ['#FF0000', '#FFCC00', '#993366', '#000080','#FF99CC'],
                            chart: {
                            type: 'column',
                            margin: 75,
                            options3d: {
                                enabled: true,
                                alpha: 21,
                                beta: 15,
                                depth: 70
                            }
                            },
                            title: {
                                text: 'รายการข้อมูลการใช้แบตเตอรี่ เครื่องสำรองไฟ'
                            },
                            xAxis: {
                                type: 'category'
                            },
                            yAxis: {
                                title: {
                                    text: '<b>จำนวน</b>'
                                },
                            },
                            legend: {
                                enabled: true
                            },
                            plotOptions: {
                                series: {
                                    borderWidth: 0,
                                    dataLabels: {
                                        enabled: true
                                    }
                                }
                            },
                            series: [
                            {
                                name: 'ปีงบประมาณ',
                                colorByPoint: true,
                                data:$main

                            }
                            ],

                        });
                    });");
                             ?>
                             <?php } ?>
                         </div>
                   </div>
              </div>
			  </div>
        <div class="col-md-6">
                    <div class="panel panel-success">
                        <div class="panel-heading"><i class="glyphicon glyphicon-list-alt"></i> การจัดซื้อใหม่อุปกรณ์ประเภทแบตเตอรี่</<i></div>
                        <div class="panel-body">
                            <div>
                                 <?php
                                Pjax::begin();
                                echo Highcharts::widget([
								 'scripts' => [
                                        'highcharts-more', // enables supplementary chart types (gauge, arearange, columnrange, etc.)
                                    //'modules/exporting', // adds Exporting button/menu to chart
                                    //'themes/grid', // applies global 'grid' theme to all charts
                                    'highcharts-3d',
                                    'modules/drilldown'
                                    ],
                                    'options' => [
                                        'title' => ['text' => 'ปีงบประมาณ'],
                                        'xAxis' => [
                                            'categories' => $year
                                        ],
                                        'yAxis' => [
                                            'title' => ['text' => 'ราคา(บาท) ']
                                        ],
                                        'series' => [
                                            ['type' => 'column',
                                                'name' => 'ราคา (รวม)',
                                                'data' => $total,
                                                'format' => ['decimal', 0]
                                            ],
                                            // ['type' => 'column',
                                            //     'name' => 'จำนวน (เครื่อง)',
                                            //     'data' => $Total,
                                            // ],
                                        ]
                                    ]
                                ]);
                                Pjax::end();
                                ?>
                            </div>


                            <div>
                                <?php
                                //use yii\grid\GridView;

                                echo GridView::widget([
                                    'dataProvider' => $dataProvider,
                                    'responsive' => true,
                                    'hover' => true,
                                    'panel' => [
                                        'before' => ' ',
                                    ],
                                    'pjax' => true,
                                    'pjaxSettings' => [
                                        'neverTimeout' => true,
                                    ],
                                    
                                ]);
                                ?>
                            </div>
                        </div>
                    </div>
              </div>
			   </div>
           <div class="panel-body">
          
<div class="col-md-6">
<div class="panel panel-danger">
<div class="panel-heading"><i class="glyphicon glyphicon-list-alt"></i> รายการข้อมูลย้อนหลังSoftware</<i></div>
<div class="panel-body">
<div style="display: none">
                                <?php
                                echo Highcharts::widget([
                                    'scripts' => [
                                        'highcharts-more', // enables supplementary chart types (gauge, arearange, columnrange, etc.)
                                    //'modules/exporting', // adds Exporting button/menu to chart
                                    //'themes/grid', // applies global 'grid' theme to all charts
                                    'highcharts-3d',
                                    'modules/drilldown'
                                    ]
                                ]);
                                ?>
                            </div>
                            <div id="chart_topopdx">
                            </div>
                             <?php
                            $sql = "SELECT a.fiscal,
                            COUNT(CASE WHEN(b.comcategory_id = 1) THEN 1 END )  AS  'Software'
                            FROM com_fiscal a
                            INNER JOIN jobtype b ON a.type_id = b.id
                            INNER JOIN com_categories c ON b.comcategory_id = c.comcategory_id
                            GROUP BY a.fiscal";
                            $rawData = Yii::$app->db->createCommand($sql)->queryAll();
                            $main_data = [];
                            foreach ($rawData as $data) {
                                $main_data[] = [
                                    'name' => $data['fiscal'],
                                    'y' => $data['Software'] * 1,
                                ];
                                $main = json_encode($main_data);
                                ?>

                                <?php
                                $this->registerJs("$(function () {
                        $('#chart_topopdx').highcharts({
                            colors: ['#CC99FF', '#C0C0C0', '#B9D300', '#FFFF99', '#99CC00', '#FF9900', '#33CCCC', '#FF99CC', '#808000', '#FF0000', '#FFCC00', '#993366', '#000080'],
                            chart: {
                            type: 'column',
                            margin: 75,
                            options3d: {
                                enabled: true,
                                alpha: 21,
                                beta: 15,
                                depth: 70
                            }
                            },
                            title: {
                                text: 'รายการข้อมูลย้อนหลังแจ้งเรื่องปัญหาSoftware'
                            },
                            xAxis: {
                                type: 'category'
                            },
                            yAxis: {
                                title: {
                                    text: '<b>จำนวน</b>'
                                },
                            },
                            legend: {
                                enabled: true
                            },
                            plotOptions: {
                                series: {
                                    borderWidth: 0,
                                    dataLabels: {
                                        enabled: true
                                    }
                                }
                            },
                            series: [
                            {
                                name: 'ปีงบประมาณ',
                                colorByPoint: true,
                                data:$main

                            }
                            ],

                        });
                    });");
                             ?>
                             <?php } ?>
                         </div>
                   </div>
              </div>
		<div class="col-md-6">
        <div class="panel panel-danger">
            <div class="panel-heading"><i class="glyphicon glyphicon-list-alt"></i> รายการข้อมูลย้อนหลังHardware</<i></div>
                <div class="panel-body">
                    <div style="display: none">
                                <?php
                                echo Highcharts::widget([
                                    'scripts' => [
                                        'highcharts-more', // enables supplementary chart types (gauge, arearange, columnrange, etc.)
                                    //'modules/exporting', // adds Exporting button/menu to chart
                                    //'themes/grid', // applies global 'grid' theme to all charts
                                    'highcharts-3d',
                                    'modules/drilldown'
                                    ]
                                ]);
                                ?>
                            </div>
                            <div id="chart_hardware">
                            </div>
                             <?php
                            $sql = "SELECT k.fiscal,
									 COUNT(CASE WHEN(k.comcategory_id = 2) THEN 2 END )  AS  'Hardware'               
									FROM (
									SELECT a.id, a.detail, a.dateline, a.send_by, a.repair_by , a.repair_at, a.repair_service, b.comcategory_id, a.jstatus_id, a.type_id, a.dep_id
									,IF(MONTH(a.repair_at)>9, YEAR(a.repair_at)+544, YEAR(a.repair_at)+543) AS fiscal
									FROM jobcom a
									INNER JOIN jobtype b ON a.type_id = b.id
									INNER JOIN com_categories c ON b.comcategory_id = c.comcategory_id) as k
									 GROUP BY k.fiscal";
                            $rawData = Yii::$app->db->createCommand($sql)->queryAll();
                            $main_data = [];
                            foreach ($rawData as $data) {
                                $main_data[] = [
                                    'name' => $data['fiscal'],
                                    'y' => $data['Hardware'] * 1,
                                ];
                                $main = json_encode($main_data);
                                ?>

                                <?php
                                $this->registerJs("$(function () {
                        $('#chart_hardware').highcharts({
                            colors: ['#FF0000', '#FFCC00', '#993366', '#000080'],
                            chart: {
                            type: 'column',
                            margin: 75,
                            options3d: {
                                enabled: true,
                                alpha: 21,
                                beta: 15,
                                depth: 70
                            }
                            },
                            title: {
                                text: 'รายการข้อมูลย้อนหลังแจ้งเรื่องปัญหาHardware'
                            },
                            xAxis: {
                                type: 'category'
                            },
                            yAxis: {
                                title: {
                                    text: '<b>จำนวน</b>'
                                },
                            },
                            legend: {
                                enabled: true
                            },
                            plotOptions: {
                                series: {
                                    borderWidth: 0,
                                    dataLabels: {
                                        enabled: true
                                    }
                                }
                            },
                            series: [
                            {
                                name: 'ปีงบประมาณ',
                                colorByPoint: true,
                                data:$main

                            }
                            ],

                        });
                    });");
                             ?>
                             <?php } ?>
                            <div>
                      </div>
                  </div>
             </div>
         </div>
		   