<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use \miloschuman\highcharts\Highcharts;
use yii\web\JsExpression;
use kartik\grid\GridView;
use kartik\tabs\TabsX;
use kartik\export\ExportMenu;
use yii\bootstrap4\LinkPager;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;
use yii\bootstrap4\Breadcrumbs;

/* @var $this yii\web\View */

$this->title = 'Dashboard';
?>
    <div class="panel-body">
    <div class="panel panel-default">
        <div class="panel-heading" ><i class="glyphicon glyphicon-plus"></i> Replication</<i></div>
        <div class="panel-body">
            
<!-- ####################################Start Replication######### -->


     <div class="row">
                <div class="col-md-4">
                    <div class="panel panel-info">
                        <div class="panel-heading" ><i class="glyphicon glyphicon-user"></i> SLAVE192.168.200.2</<i></div>
                        <div class="panel-body">
                            <div>
                       
                                <?php
                                //use yii\grid\GridView;

                                echo GridView::widget([
                                    'dataProvider' => $data14Provider,
                                    'responsive' => true,
                                    'showFooter' => false,
                                    'summary'=>'',
                                  
                                    'pjax' => true,
                                    'pjaxSettings' => [
                                        'neverTimeout' => true,
                                    ],
                                    'columns' => [
                                       // ['class' => 'yii\grid\SerialColumn'],
                                        
                                        [
                                            'label' => 'แฟ้ม',
											'attribute' => 'tables',
											'headerOptions' => ['style' => 'background-color: #; color: #3498DB;'], // กำหนดสีพื้นหลังและสีตัวอักษรของ label
											//'contentOptions' => ['style' => 'background-color: #EBF5FB; color:#;'], // กำหนดสีตัวอักษรของข้อมูลในคอลัมน์                                          
                                        ],
                                        [
                                            'label' => 'จำนวน',
                                            'attribute' => 'amount',
											'headerOptions' => ['style' => 'background-color: #; color: #3498DB;'],
                                            'format' => ['decimal', 0]
                                        ],
                                        

                                    ],
                                ]);
                                ?>
                            </div>
                          
                            </div></div></div>
                <div class="col-md-4">
                    <div class="panel panel-info">
                        <div class="panel-heading" ><i class="glyphicon glyphicon-user"></i> SLAVE192.168.200.70</<i></div>
                        <div class="panel-body">
                            <div>
                       
                                <?php
                                //use yii\grid\GridView;

                                echo GridView::widget([
                                    'dataProvider' => $data70Provider,
                                    'responsive' => true,
                                    'showFooter' => false,
                                    'summary'=>'',
                                  
                                    'pjax' => true,
                                    'pjaxSettings' => [
                                        'neverTimeout' => true,
                                    ],
                                    'columns' => [
                                       // ['class' => 'yii\grid\SerialColumn'],
                                        
                                        [
                                            'label' => 'แฟ้ม',
                                            'attribute' => 'tables',
											'headerOptions' => ['style' => 'background-color: #; color: #3498DB;'],
                                           // 'format' => ['decimal', 0]
                                        ],
                                        [
                                            'label' => 'จำนวน',
                                            'attribute' => 'amount',
											'headerOptions' => ['style' => 'background-color: #; color: #3498DB;'],
                                            'format' => ['decimal', 0]
                                        ],
                                        

                                    ],
                                ]);
                                ?>
                            </div>
                            </div></div></div>
                            <div class="col-md-4">
                    <div class="panel panel-info">
                        <div class="panel-heading"><i class="glyphicon glyphicon-user"></i>MASTER 192.168.200.7</<i></div>
                        <div class="panel-body">
                            <div>
                       
                                <?php
                                //use yii\grid\GridView;

                                echo GridView::widget([
                                    'dataProvider' => $data7Provider,
                                    'responsive' => true,
                                   'showFooter' => false,
                                    'summary'=>'',
                                  
                                    'pjax' => true,
                                    'pjaxSettings' => [
                                        'neverTimeout' => true,
                                    ],
                                    'columns' => [
                                       // ['class' => 'yii\grid\SerialColumn'],
                                        
                                        [
                                            'label' => 'แฟ้ม',
                                            'attribute' => 'tables',
											'headerOptions' => ['style' => 'background-color: #; color: #3498DB;'],
                                           // 'format' => ['decimal', 0]
                                        ],
                                        [
                                            'label' => 'จำนวน',
                                            'attribute' => 'amount',
											'headerOptions' => ['style' => 'background-color: #; color: #3498DB;'],
                                            'format' => ['decimal', 0]
                                        ],
										
                                    ],
                                ]);
                                ?>
                            </div>
                            </div></div></div></div></div></div>
	
    <div class="panel panel-default">
        <div class="panel-heading"><i class="glyphicon glyphicon-plus"></i> Monitor Server</<i></div>
        <div class="panel-body">
            
<!-- ####################################Start Replication######### -->


     <div class="row">
                <div class="col-md-4">
                    <div class="panel panel-info">
                        <div class="panel-heading"<i class="glyphicon glyphicon-user"></i>DHDC Hosting (พื้นที่ 10 GB)</<i></div>
                        <div class="panel-body">
                            <div>
                       
                                <?php
                                //use yii\grid\GridView;

                                echo GridView::widget([
                                 //   'dataProvider' => $dhdcProvider,
                                    'responsive' => true,
                                   'showFooter' => false,
                                    'summary'=>'',
                                  
                                    'pjax' => true,
                                    'pjaxSettings' => [
                                        'neverTimeout' => true,
                                    ],
                                    'columns' => [
                                       // ['class' => 'yii\grid\SerialColumn'],
                                        
                                        [
                                            'label' => 'แฟ้ม',
                                            'attribute' => 'table_name',
											'headerOptions' => ['style' => 'background-color: #; color: #3498DB;'],
                                            //'format' => ['decimal', 0]
                                        ],
										[
                                            'label' => 'จำนวนแถว',
                                            'attribute' => 'table_rows',
											'headerOptions' => ['style' => 'background-color: #; color: #3498DB;'],
                                            'format' => ['decimal', 0]
                                        ],
										/*
										[
                                            'label' => 'ขนาด(Mb)',
                                            'attribute' => 'total_size_mb',
                                            'format' => ['decimal', 0]
                                        ],
										*/
										[
                                            'label' => 'ขนาด(Gb)',
                                            'attribute' => 'total_size_gb',
											'headerOptions' => ['style' => 'background-color: #; color: #3498DB;'],
                                           // 'format' => ['decimal', 0]
                                        ],    
                                    ],
                                ]);
                                ?>
                            </div>
                          
                            </div></div></div>
                <div class="col-md-4">
                    <div class="panel panel-info">
                        <div class="panel-heading" ><i class="glyphicon glyphicon-user"></i> SLAVE192.168.200.14</<i></div>
                        <div class="panel-body">
                            <div>
							  <?php
                                //use yii\grid\GridView;

                                echo GridView::widget([
                                    'dataProvider' => $mbase14Provider,
                                    'responsive' => true,
                                   'showFooter' => false,
                                    'summary' => '',
                                  
                                    'pjax' => true,
                                    'pjaxSettings' => [
                                        'neverTimeout' => true,
                                    ],
                                    'columns' => [
                                       // ['class' => 'yii\grid\SerialColumn'],
                                        
                                        [
                                            'label' => 'แฟ้ม',
                                            'attribute' => 'table_name',
											'headerOptions' => ['style' => 'background-color: #; color: #3498DB;'],
                                            //'format' => ['decimal', 0]
                                        ],
										[
                                            'label' => 'จำนวนแถว',
                                            'attribute' => 'table_rows',
											'headerOptions' => ['style' => 'background-color: #; color: #3498DB;'],
                                            'format' => ['decimal', 0]
                                        ],
										/*
										[
                                            'label' => 'ขนาด(Mb)',
                                            'attribute' => 'total_size_mb',
                                            'format' => ['decimal', 0]
                                        ],
										*/
										[
                                            'label' => 'ขนาด(Gb)',
                                            'attribute' => 'total_size_gb',
											'headerOptions' => ['style' => 'background-color: #; color: #3498DB;'],
                                           // 'format' => ['decimal', 0]
                                        ],    
                                    ],
                                ]);
                                ?>
                            </div>
                            </div></div></div>
                           <div class="col-md-4">
                    <div class="panel panel-info">
                        <div class="panel-heading" ><i class="glyphicon glyphicon-user"></i> MASTER192.168.200.7</<i></div>
                        <div class="panel-body">
                            <div>
							  <?php
                                //use yii\grid\GridView;

                                echo GridView::widget([
                                    'dataProvider' => $mbase14Provider,
                                    'responsive' => true,
                                   'showFooter' => false,
                                    'summary' => '',
                                  
                                    'pjax' => true,
                                    'pjaxSettings' => [
                                        'neverTimeout' => true,
                                    ],
                                    'columns' => [
                                       // ['class' => 'yii\grid\SerialColumn'],
                                        
                                        [
                                            'label' => 'แฟ้ม',
                                            'attribute' => 'table_name',
											'headerOptions' => ['style' => 'background-color: #; color: #3498DB;'],
                                            //'format' => ['decimal', 0]
                                        ],
										[
                                            'label' => 'จำนวนแถว',
                                            'attribute' => 'table_rows',
											'headerOptions' => ['style' => 'background-color: #; color: #3498DB;'],
                                            'format' => ['decimal', 0]
                                        ],
										/*
										[
                                            'label' => 'ขนาด(Mb)',
                                            'attribute' => 'total_size_mb',
                                            'format' => ['decimal', 0]
                                        ],
										*/
										[
                                            'label' => 'ขนาด(Gb)',
                                            'attribute' => 'total_size_gb',
											'headerOptions' => ['style' => 'background-color: #; color: #3498DB;'],
                                           // 'format' => ['decimal', 0]
                                        ],    
                                    ],
                                ]);
                                ?>
                            </div>
                            </div></div></div>
                            </div></div></div></div>


                            
                            
                            
                            
                   
                            
         