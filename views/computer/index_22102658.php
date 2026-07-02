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
                        <div class="panel-heading"><i class="glyphicon glyphicon-user"></i> แยกการจัดซื้อใหม่ทั้งทดแทนและขยายงาน</<i></div>
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
                                            'categories' => $cfiscal
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
                                    'dataProvider' => $comdataProvider,
                                    'responsive' => true,
                                    'hover' => true,
                                    'panel' => [
                                        'before' => ' ',
                                    ],
                                    'pjax' => true,
                                    'pjaxSettings' => [
                                        'neverTimeout' => true,
                                    ],
                                    'columns' => [
                                        ['class' => 'yii\grid\SerialColumn'],
                                        [
                                            'label' => 'ปีงบ',
                                            'attribute' => 'fiscal'
                                        ],
                                        [
                                            'label' => 'PC',
                                            'attribute' => 'PC',
                                        ],
                                        [
                                            'label' => 'NB',
                                            'attribute' => 'NB',
                                        ],
                                        [
                                            'label' => 'PLaser',
                                            'attribute' => 'PrinLaser',
                                        ],
                                        [
                                            'label' => 'PInk',
                                            'attribute' => 'PrinInk',
                                        ],
                                        [
                                            'label' => 'UPS',
                                            'attribute' => 'UPS',
                                        ],
                                        [
                                            'label' => 'LCD',
                                            'attribute' => 'LCD',
                                        ],
                                        [
                                            'label' => 'Termal',
                                            'attribute' => 'Termal',
                                        ],
                                        [
                                            'label' => 'Scan',
                                            'attribute' => 'Scan',
                                        ],
										[
                                            'label' => 'Ipad',
                                            'attribute' => 'Ipad',
                                        ],
										/*
                                        [
                                            'label' => 'รวม',
                                            'attribute' => 'Total',
                                        ],
										*/
                                        [
                                            'label' => 'ราคารวม',
                                            'attribute' => 'Price',
                                            'format' => ['decimal', 0]
                                        ],
                                        
                                    ],
                                ]);
                                ?>
                            </div>

                            <div class="kv-panel-pager">
                              
                           </div>
                        </div>
                    </div>
                </div>
        <div class="col-md-6">
                    <div class="panel panel-success">
                        <div class="panel-heading"><i class="glyphicon glyphicon-list-alt"></i> สรุปจำนวนเครื่องคอมพิวเตอร์ทั้งหมดที่ยังใช้งานได้</<i></div>
                        <div class="panel-body">
                            <div>
                                <?php
                                Pjax::begin();
                                echo Highcharts::widget([

                                    'options' => [
                                        'colors' => ['#CC99FF', '#C0C0C0', '#B9D300', '#FFFF99', '#99CC00', '#FF9900', '#33CCCC', '#FF99CC', '#808000', '#FF0000', '#FFCC00', '#993366', '#000080'],
                                        'title' => ['text' => 'คอมพิวเตอร์ปัจจุบัน'],
                                        'xAxis' => [
                                            'categories' => 'ประเภท'
                                        ],
                                        'yAxis' => [
                                            'title' => ['text' => 'จำนวน (เครื่อง) ']
                                        ],
                                        'series' => [
                                            ['type' => 'column',
                                                'name' => 'pc',
                                                'data' => $pc,
                                            //'color' => '#F5C4B6',
                                            ],
                                            ['type' => 'column',
                                                'name' => 'nb',
                                                'data' => $nb,
                                            ],
                                            ['type' => 'column',
                                                'name' => 'laser',
                                                'data' => $laser,
                                            ],
                                            ['type' => 'column',
                                                'name' => 'ink',
                                                'data' => $ink,
                                            ],
                                            ['type' => 'column',
                                                'name' => 'termal',
                                                'data' => $termal,
                                            ],
                                            ['type' => 'column',
                                                'name' => 'scan',
                                                'data' => $scan,
                                            ],
											['type' => 'column',
                                                'name' => 'ipad',
                                                'data' => $ipad,
                                            ],
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
                                    'dataProvider' => $cdataProvider,
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
    <div class="panel panel-warning" >
        <div class="panel-heading" id="grad0"><i class="glyphicon glyphicon-plus-sign"></i> ระบบรายงานส่งซ่อมคอมพิวเตอร์และอุปกรณ์ต่อพ่วง</<i></div>
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
                            <div id="chart_topopd">
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
                        $('#chart_topopd').highcharts({
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
    <div>
	 <div class="col-md-6">
        <div class="panel panel-info">
            <div class="panel-heading"><i class="glyphicon glyphicon-list-alt"></i> รายการข้อมูลย้อนหลังmBase</<i></div>
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
                            <div id="chart_mbase">
                            </div>
                             <?php
                            $sql = "SELECT k.fiscal,
								 COUNT(CASE WHEN(k.comcategory_id = 3) THEN 3 END )  AS  'mBase'                         
								FROM (
								SELECT a.id, a.detail, a.dateline, a.send_by, a.repair_by , a.repair_at, a.repair_service, b.comcategory_id, a.jstatus_id, a.type_id, a.dep_id
								, IF(MONTH(a.repair_at)>9, YEAR(a.repair_at)+544, YEAR(a.repair_at)+543) AS fiscal
								FROM jobcom a
								INNER JOIN jobtype b ON a.type_id = b.id
								INNER JOIN com_categories c ON b.comcategory_id = c.comcategory_id) as k
								 GROUP BY k.fiscal";
                            $rawData = Yii::$app->db->createCommand($sql)->queryAll();
                            $main_data = [];
                            foreach ($rawData as $data) {
                                $main_data[] = [
                                    'name' => $data['fiscal'],
                                    'y' => $data['mBase'] * 1,
                                ];
                                $main = json_encode($main_data);
                                ?>

                                <?php
                                $this->registerJs("$(function () {
                        $('#chart_mbase').highcharts({
                            colors: ['#FFCC00', '#993366', '#000080'],
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
                                text: 'รายการข้อมูลย้อนหลังแจ้งเรื่องปัญหาโปรแกรมmBase'
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
                    </div>
                    
    <div class="col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading"><i class="glyphicon glyphicon-list-alt"></i> รายการข้อมูลย้อนหลังInternet</<i></div>
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
                            <div id="chart_internet">
                            </div>
                             <?php
                            $sql = "SELECT k.fiscal,
								 COUNT(CASE WHEN(k.comcategory_id = 4) THEN 4 END )  AS  'Internet'              
								FROM (
								SELECT a.id, a.detail, a.dateline, a.send_by, a.repair_by , a.repair_at, a.repair_service, b.comcategory_id, a.jstatus_id, a.type_id, a.dep_id
								, IF(MONTH(a.repair_at)>9, YEAR(a.repair_at)+544, YEAR(a.repair_at)+543) AS fiscal
								FROM jobcom a
								INNER JOIN jobtype b ON a.type_id = b.id
								INNER JOIN com_categories c ON b.comcategory_id = c.comcategory_id) as k
								 GROUP BY k.fiscal";
                            $rawData = Yii::$app->db->createCommand($sql)->queryAll();
                            $main_data = [];
                            foreach ($rawData as $data) {
                                $main_data[] = [
                                    'name' => $data['fiscal'],
                                    'y' => $data['Internet'] * 1,
                                ];
                                $main = json_encode($main_data);
                                ?>

                                <?php
                                $this->registerJs("$(function () {
                        $('#chart_internet').highcharts({
                            colors: [ '#CC99FF','#993366', '#000080'],
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
                                text: 'รายการข้อมูลย้อนหลังแจ้งเรื่องปัญหาInternetใช้งานไม่ได้'
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

 <div>
    <div class="col-md-6">
        <div class="panel panel-info">
            <div class="panel-heading"><i class="glyphicon glyphicon-list-alt"></i> รายการข้อมูลย้อนหลังServer</<i></div>
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
                            <div id="chart_server">
                            </div>
                             <?php
                            $sql = "SELECT k.fiscal,
								 COUNT(CASE WHEN(k.comcategory_id = 6) THEN 6 END )  AS  'Server'          
								FROM (
								SELECT a.id, a.detail, a.dateline, a.send_by, a.repair_by , a.repair_at, a.repair_service, b.comcategory_id, a.jstatus_id, a.type_id, a.dep_id
								, IF(MONTH(a.repair_at)>9, YEAR(a.repair_at)+544, YEAR(a.repair_at)+543) AS fiscal
								FROM jobcom a
								INNER JOIN jobtype b ON a.type_id = b.id
								INNER JOIN com_categories c ON b.comcategory_id = c.comcategory_id) as k
								GROUP BY k.fiscal";
                            $rawData = Yii::$app->db->createCommand($sql)->queryAll();
                            $main_data = [];
                            foreach ($rawData as $data) {
                                $main_data[] = [
                                    'name' => $data['fiscal'],
                                    'y' => $data['Server'] * 1,
                                ];
                                $main = json_encode($main_data);
                                ?>

                                <?php
                                $this->registerJs("$(function () {
                        $('#chart_server').highcharts({
                            colors: ['#99CC00', '#993366', '#000080'],
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
                                text: 'รายการข้อมูลย้อนหลังแจ้งเรื่องปัญหาServerล่มเกิน 60 นาที'
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
                    </div>
                    
    <div class="col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading"><i class="glyphicon glyphicon-list-alt"></i> รายการข้อมูลย้อนหลังดูกล้องวงจรปิด</<i></div>
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
                            <div id="chart_camera">
                            </div>
                             <?php
                            $sql = "SELECT k.fiscal,
								 COUNT(CASE WHEN(k.comcategory_id = 10) THEN 10 END )  AS  'camera'     
								FROM (
								SELECT a.id, a.detail, a.dateline, a.send_by, a.repair_by , a.repair_at, a.repair_service, b.comcategory_id, a.jstatus_id, a.type_id, a.dep_id
								, IF(MONTH(a.repair_at)>9, YEAR(a.repair_at)+544, YEAR(a.repair_at)+543) AS fiscal
								FROM jobcom a
								INNER JOIN jobtype b ON a.type_id = b.id
								INNER JOIN com_categories c ON b.comcategory_id = c.comcategory_id) as k
								 GROUP BY k.fiscal";
                            $rawData = Yii::$app->db->createCommand($sql)->queryAll();
                            $main_data = [];
                            foreach ($rawData as $data) {
                                $main_data[] = [
                                    'name' => $data['fiscal'],
                                    'y' => $data['camera'] * 1,
                                ];
                                $main = json_encode($main_data);
                                ?>

                                <?php
                                $this->registerJs("$(function () {
                        $('#chart_camera').highcharts({
                            colors: [ '#CC99FF','#993366', '#000080'],
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
                                text: 'รายการข้อมูลย้อนหลังแจ้งดูกล้องวงจรปิด'
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
			 <div class="col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading"><i class="glyphicon glyphicon-list-alt"></i> รายการข้อมูลเปลี่ยนตลับหมึก</<i></div>
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
                            <div id="chart_ink">
                            </div>
                             <?php
                            $sql = "SELECT k.fiscal,
								 COUNT(CASE WHEN(k.comcategory_id = 7) THEN 7 END )  AS  'Ink'     
								FROM (
								SELECT a.id, a.detail, a.dateline, a.send_by, a.repair_by , a.repair_at, a.repair_service, b.comcategory_id, a.jstatus_id, a.type_id, a.dep_id
								, IF(MONTH(a.repair_at)>9, YEAR(a.repair_at)+544, YEAR(a.repair_at)+543) AS fiscal
								FROM jobcom a
								INNER JOIN jobtype b ON a.type_id = b.id
								INNER JOIN com_categories c ON b.comcategory_id = c.comcategory_id) as k
								 GROUP BY k.fiscal";
                            $rawData = Yii::$app->db->createCommand($sql)->queryAll();
                            $main_data = [];
                            foreach ($rawData as $data) {
                                $main_data[] = [
                                    'name' => $data['fiscal'],
                                    'y' => $data['Ink'] * 1,
                                ];
                                $main = json_encode($main_data);
                                ?>

                                <?php
                                $this->registerJs("$(function () {
                        $('#chart_ink').highcharts({
                            colors: [ '#CC99FF','#993366', '#000088'],
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
                                text: 'รายการข้อมูลประเภทหมึก'
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
			   
			   <div class="col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading"><i class="glyphicon glyphicon-list-alt"></i> รายการส่งซ่อมคอมพิวเตอร์</<i></div>
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
                            <div id="comsod">
                            </div>
                             <?php
                            $sql = "SELECT k.fiscal,
 
                            COUNT(CASE WHEN(k.comcategory_id = 1) THEN 1 END )  AS  'Software',
                            COUNT(CASE WHEN(k.comcategory_id = 2) THEN 2 END )  AS  'Hardware',
                            COUNT(CASE WHEN(k.comcategory_id = 3) THEN 3 END )  AS  'mBase',
                            COUNT(CASE WHEN(k.comcategory_id = 4) THEN 4 END )  AS  'Internet',
                            #COUNT(CASE WHEN(k.comcategory_id = 5) THEN 5 END )  AS  'Sod',
                            COUNT(CASE WHEN(k.comcategory_id = 6) THEN 6 END )  AS  'Server',
                            COUNT(CASE WHEN(k.comcategory_id = 7) THEN 7 END )  AS  'Ink',
                            COUNT(CASE WHEN(k.comcategory_id = 8) THEN 8 END )  AS  'other',
                            COUNT(CASE WHEN(k.comcategory_id = 9) THEN 9 END )  AS  'metting',
                            COUNT(CASE WHEN(k.comcategory_id = 10) THEN 10 END )  AS  'camera',
                            COUNT(CASE WHEN(k.comcategory_id BETWEEN 1 AND 10 ) THEN 11 END )  AS  'Total'
               
							FROM (
							SELECT a.id, a.detail, a.dateline, a.send_by, a.repair_by , a.repair_at, a.repair_service, b.comcategory_id, a.jstatus_id, a.type_id, a.dep_id
							, IF(MONTH(a.repair_at)>9, YEAR(a.repair_at)+544, YEAR(a.repair_at)+543) AS fiscal
							FROM jobcom a
							INNER JOIN jobtype b ON a.type_id = b.id
							INNER JOIN com_categories c ON b.comcategory_id = c.comcategory_id) as k
							 GROUP BY k.fiscal";
                            $rawData = Yii::$app->db->createCommand($sql)->queryAll();
                            $main_data = [];
                            foreach ($rawData as $data) {
                                $main_data[] = [
                                    'name' => $data['fiscal'],
                                    //'y' => $data['Software'] * 1,
                                    'y' => intval($data['Total']),
                                  // 'y' => [intval($data['Total']),intval($data['mBase'])],

                                ];
                                $main = json_encode($main_data);
                                ?>
                                <?php
                                $this->registerJs("$(function () {
                        $('#comsod').highcharts({
                            colors: [ '#CC99FF','#993366', '#000080'],
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
                                text: 'รายการส่งซ่อมคอมพิวเตอร์'
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
	
 <div class="col-md-6">
    
	        <div class="panel-heading" style="background: linear-gradient(90deg, #8BC34A, #64B5F6, #FFEB3B); color: #ffffff;">
        <div class="panel-heading"><i class="glyphicon glyphicon-list-alt"></i> ความพึงพอใจผู้รับบริการ</div>
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
            <div id="comsodx">
            </div>
            <?php
            $main_data = [
               ['name' => '2562', 'y' => 60],  
				['name' => '2563', 'y' => 62],
                ['name' => '2564', 'y' => 65],
                ['name' => '2565', 'y' => 67],
                ['name' => '2566', 'y' => 68],
				['name' => '2567', 'y' => 0],
                // Add more data as needed
            ];
            $main = json_encode($main_data);
            $this->registerJs("$(function () {
                $('#comsodx').highcharts({
                    colors: ['#CC99FF','#993366', '#000080'],
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
                        text: 'ความพึงพอใจ'
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
                        data: $main
                    }
                    ],
                });
            });");
            ?>
        </div>
    </div>
</div>
<div class="col-md-6">
     <div class="panel-heading" style="background: linear-gradient(90deg, #CC99FF, #993366, #FF0000, #FFCC00, #9933FF, #000080); color: #ffffff;">
        <div class="panel-heading"><i class="glyphicon glyphicon-list-alt"></i> อัตราการสูญหายของเวชระเบียนผู้ป่วยใน </div>
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
            <div id="comsodxx">
            </div>
            <?php
            $main_data = [
				['name' => '2562', 'y' => 0],
				['name' => '2563', 'y' => 0],
                ['name' => '2564', 'y' => 0],
                ['name' => '2565', 'y' => 0],
                ['name' => '2566', 'y' => 0],
				['name' => '2567', 'y' => 0],
                // Add more data as needed
            ];
            $main = json_encode($main_data);
            $this->registerJs("$(function () {
                $('#comsodxx').highcharts({
                    colors: ['#CC99FF','#993366' ,'#FF0000', '#FFCC00', '#9933FF', '#000080'],
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
                        text: 'ผู้ป่วยใน'
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
                        data: $main
                    }
                    ],
                });
            });");
            ?>
        </div>
    </div>
</div>
</div>
<div class="col-md-6">
    <div class="panel panel-primary">
        <div class="panel-heading" style="background: linear-gradient(90deg, #8BC34A, #64B5F6, #FFEB3B); color: #ffffff;">
            <i class="glyphicon glyphicon-list-alt"></i> จำนวนให้บริการข้อมูลการเงินเครือข่าย  และ EHR เครือข่ายม่วงสามสิบ
        </div>
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
            <div id="ehr"></div>
            <?php
            // Fetch data from the database
            $sql = "SELECT 
                        IF(MONTH(lg.datetime) > 9, YEAR(lg.datetime) + 544, YEAR(lg.datetime) + 543) as fiscal_year,
                        count(DISTINCT lg.username) as users, 
                        count(lg.patient_cid) as cid
                    FROM log_ehr lg 
                    WHERE lg.datetime BETWEEN '2019-01-01' AND '2024-12-31'
                    GROUP BY fiscal_year
                    ORDER BY fiscal_year";

            $rawData = Yii::$app->db_ehr->createCommand($sql)->queryAll();

            $categories = [];
            $users_data = [];
            $cid_data = [];

            foreach ($rawData as $data) {
                $categories[] = intval($data['fiscal_year']);
                $users_data[] = intval($data['users']);
                $cid_data[] = intval($data['cid']);
            }

            $this->registerJs("$(function () {
                $('#ehr').highcharts({
                    colors: ['#64B5F6', '#FFEB3B'], // Colors for users and cid
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
                        text: 'จำนวนผู้ใช้งาน EHR'
                    },
                    xAxis: {
                        categories: " . json_encode($categories) . ",
                        title: {
                            text: '<b>ปีงบประมาณ</b>'
                        },
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
                        column: {
                            dataLabels: {
                                enabled: true,
                                format: '{point.y}'
                            }
                        }
                    },
                    series: [
                        {
                            name: 'Users',
                            data: " . json_encode($users_data) . "
                        },
                        {
                            name: 'CID',
                            data: " . json_encode($cid_data) . "
                        }
                    ],
                });
            });");
            ?>
        </div>
    </div>
</div>



<div class="col-md-6">
     <div class="panel-heading" style="background: linear-gradient(90deg, #CC99FF, #993366, #FF0000, #FFCC00, #9933FF, #000080); color: #ffffff;">
        <div class="panel-heading"><i class="glyphicon glyphicon-list-alt"></i> อัตราการสูญหายของเวชระเบียนผู้ป่วยใน </div>
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
            <div id="comsodxx11">
            </div>
            <?php
            $main_data = [
				['name' => '2562', 'y' => 0],
				['name' => '2563', 'y' => 0],
                ['name' => '2564', 'y' => 0],
                ['name' => '2565', 'y' => 0],
                ['name' => '2566', 'y' => 0],
				['name' => '2567', 'y' => 0],
                // Add more data as needed
            ];
            $main = json_encode($main_data);
            $this->registerJs("$(function () {
                $('#comsodxx11').highcharts({
                    colors: ['#CC99FF','#993366' ,'#FF0000', '#FFCC00', '#9933FF', '#000080'],
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
                        text: 'ผู้ป่วยใน'
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
                        data: $main
                    }
                    ],
                });
            });");
            ?>
        </div>
    </div>
</div>
</div>
</div>

<div class="col-md-6">
    <div class="panel panel-primary">
        <div class="panel-heading" style="background: linear-gradient(90deg, #8BC34A, #64B5F6, #9933FF); color: #ffffff;">
            <i class="glyphicon glyphicon-list-alt"></i> ความสมบูรณ์เวชระเบียนและความถูกต้อง
        </div>
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
            <div id="comsodx2"></div>
            <?php
            $categories = ['2562', '2563', '2564', '2565', '2566','2567']; // Years
            $opd_data = [95.40, 88.00, 91.33, 0 , 92.22, 91.52]; // Example data for OPD
            $ipd_data = [93, 92, 91.65, 0, 87.65 ,81.69]; // Example data for IPD
			//$ipd_data = [93.56, 92.74, 91.65, 0, 87.65 ,81.69]; // Example data for IPD
            $progress_note_data = [94.56, 92.44, 76.16, 0, 63.42, 80.21]; // Example data for Progress Note
   ##OPD##    95.40 	88 	91.33 	NA 	92.22 	91.52          
   ##IPD###   93.56	92.74	91.65	NA	87.65	81.69
   ### Progress ##  94.56	92.44	76.16	NA	63.42	80.21
            $this->registerJs("$(function () {
                $('#comsodx2').highcharts({
                    colors: ['#8BC34A', '#64B5F6', '#CC99FF'],
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
                        text: 'ความสมบูรณ์เวชระเบียน'
                    },
                    xAxis: {
                        categories: " . json_encode($categories) . ",
                        title: {
                            text: '<b>ปีงบประมาณ</b>'
                        },
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
                            name: 'OPD',
                            data: " . json_encode($opd_data) . "
                        },
                        {
                            name: 'IPD',
                            data: " . json_encode($ipd_data) . "
                        },
                        {
                            name: 'Progress Note',
                            data: " . json_encode($progress_note_data) . "
                        }
                    ],
                });
            });");
            ?>
        </div>
    </div>
</div>

<div class="col-md-6">
    <div class="panel panel-primary">
        <div class="panel-heading" style="background: linear-gradient(90deg, #CC99FF, #993366, #FF0000, #FFCC00, #9933FF, #000080); color: #ffffff;">
            <i class="glyphicon glyphicon-list-alt"></i> อัตราการตอบสนองตามระยะเวลาประกัน
        </div>
        <div class="panel-body">
            <!-- ตารางข้อมูล -->
            <div class="table-responsive">
                <?php
                $sql = "
                    WITH FilteredJobs AS (
                        SELECT 
                            j.detail,
                            j.send_at,
                            j.repair_at,
                            t.type,
                            j.type_id,
                            TIMESTAMPDIFF(MINUTE, j.send_at, j.repair_at) AS response_time_minutes,
                            CASE
                                WHEN MONTH(j.send_at) >= 10 THEN YEAR(j.send_at) + 1
                                ELSE YEAR(j.send_at)
                            END AS fiscal_year
                        FROM 
                            jobcom j 
                        LEFT JOIN 
                            jobtype t ON t.id = j.type_id
                        WHERE 
                            j.send_at BETWEEN '2023-06-01 00:01' AND NOW()
                        AND 
                            j.type_id IN (4, 46, 33, 10, 45, 20, 43, 11, 24, 13, 23, 40 )
                    )
                    SELECT 
                        fiscal_year,
                        COUNT(*) AS total_jobs,
                        SUM(
                            CASE 
                                WHEN (type_id in (10,13,23,40,45) AND response_time_minutes <= 15) OR
                                     (type_id in (4,33,46) AND response_time_minutes <= 20) OR
									(type_id in (11,24) AND response_time_minutes <= 30) OR
                                     (type_id in (20,43) AND response_time_minutes <= 180)
                                THEN 1 
                                ELSE 0 
                            END
                        ) AS passed_count,
                        SUM(
                            CASE 
                                WHEN (type_id in (10,13,23,40,45) AND response_time_minutes <= 15) OR
                                     (type_id in (4,33,46) AND response_time_minutes <= 20) OR
																     (type_id in (11,24) AND response_time_minutes <= 30) OR
                                     (type_id in (20,43) AND response_time_minutes <= 180)
                                THEN 0 
                                ELSE 1 
                            END
                        ) AS failed_count,
                        (SUM(
                            CASE 
                                WHEN (type_id in (10,13,23,40,45) AND response_time_minutes <= 15) OR
                                     (type_id in (4,33,46) AND response_time_minutes <= 20) OR
																     (type_id in (11,24) AND response_time_minutes <= 30) OR
                                     (type_id in (20,43) AND response_time_minutes <= 180)
                                THEN 1 
                                ELSE 0 
                            END
                        ) / COUNT(*)) * 100 AS pass_percentage
                    FROM 
                        FilteredJobs
                    GROUP BY 
                        fiscal_year
                    ORDER BY 
                        fiscal_year
                ";
                $rawData = Yii::$app->db->createCommand($sql)->queryAll();
                ?>

                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>ปีงบประมาณ</th>
                            <th>จำนวนทั้งหมด</th>
                            <th>จำนวนที่ผ่านเกณฑ์</th>
                            <th>จำนวนที่ไม่ผ่านเกณฑ์</th>
                            <th>เปอร์เซ็นต์การผ่านเกณฑ์ (%)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rawData as $data): ?>
                            <tr>
                                <td><?php echo 'ปีงบ ' . $data['fiscal_year']; ?></td>
                                <td><?php echo intval($data['total_jobs']); ?></td>
                                <td><?php echo intval($data['passed_count']); ?></td>
                                <td><?php echo intval($data['failed_count']); ?></td>
                                <td><?php echo number_format($data['pass_percentage'], 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- กราฟ -->
            <div id="jcomxx" style="width: 100%; height: 400px;"></div>

            <?php
            $main_data = [];
            foreach ($rawData as $data) {
                $main_data[] = [
                    'name' => 'ปีงบ ' . $data['fiscal_year'],
                    'y' => intval($data['pass_percentage']),
                    'total' => intval($data['total_jobs']),
                    'passed' => intval($data['passed_count']),
                    'failed' => intval($data['failed_count']),
                ];
            }
            $main = json_encode($main_data);

            $this->registerJs("$(function () {
                $('#jcomxx').highcharts({
                    colors: ['#CC99FF', '#993366', '#FF0000', '#FFCC00', '#9933FF', '#000080'],
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
                        text: 'เปอร์เซ็นต์การส่งซ่อมคอมพิวเตอร์ที่ผ่านเกณฑ์'
                    },
                    xAxis: {
                        type: 'category',
                        title: {
                            text: '<b>ปีงบประมาณ</b>'
                        }
                    },
                    yAxis: {
                        title: {
                            text: '<b>เปอร์เซ็นต์การผ่านเกณฑ์</b>'
                        },
                        max: 100
                    },
                    legend: {
                        enabled: true
                    },
                    plotOptions: {
                        series: {
                            borderWidth: 0,
                            dataLabels: {
                                enabled: true,
                                format: '{point.y:.2f}%'
                            }
                        }
                    },
                    series: [
                    {
                        name: 'ปีงบประมาณ',
                        colorByPoint: true,
                        data: $main
                    }
                    ],
                    drilldown: {
                        series: [
                            {
                                id: 'jobs',
                                name: 'รายละเอียดการซ่อม',
                                data: $main.map(function(item) {
                                    return {
                                        name: item.name,
                                        y: item.total,
                                        drilldown: null,
                                        data: [
                                            ['ผ่านเกณฑ์', item.passed],
                                            ['ไม่ผ่านเกณฑ์', item.failed]
                                        ]
                                    };
                                })
                            }
                        ]
                    }
                });
            });");
            ?>

            <!-- ข้อความอธิบาย -->
            <div class="alert alert-info mt-4">
                <h4>ข้อมูลเพิ่มเติม:</h4>
                <ul>
                    <li><strong>ปีงบ 2023:</strong> การเก็บข้อมูลในปีงบ 2023 ไม่มีการระบุเวลา ทำให้ไม่สามารถเปรียบเทียบช่วงเวลาการตอบสนองได้อย่างแม่นยำ</li>
                    <li><strong>เงื่อนไขการประกันเวลา:</strong> 
                        <ul>
                            <li><strong>ประเภท:Mouse Keyboard สำรองไฟ</strong> งานที่มีการตอบสนองภายใน 15 นาที</li>
                            <li><strong>:ประเภทเครื่องพิมพ์</strong> งานที่มีการตอบสนองภายใน 20 นาที</li>
                            <li><strong>ประเภทระบบเครือข่ายขัดข้อง อินเตอร์เน็ตล่ม:</strong> งานที่มีการตอบสนองภายใน 30นาที</li>
							<li><strong>ลงระบบปฏิบัติการใหม่:</strong> งานที่มีการตอบสนองภายใน 180 นาที</li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
