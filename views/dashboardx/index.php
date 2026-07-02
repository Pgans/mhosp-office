<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use \miloschuman\highcharts\Highcharts;
use yii\web\JsExpression;
use kartik\grid\GridView;
use kartik\export\ExportMenu;

/* @var $this yii\web\View */

$this->title = 'Dashboard';
?>
    <div class="panel-body">
    <div class="panel panel-primary">
        <div class="panel-heading"><i class="glyphicon glyphicon-plus"></i> Dashboard</<i></div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="panel panel-info">
                        <div class="panel-heading"><i class="glyphicon glyphicon-user"></i> AuthenCode</<i></div>
                        <div class="panel-body">
                            <div>
                            <?php
                                //use yii\grid\GridView;

                                echo GridView::widget([
                                    'dataProvider' => $data2Provider,
                                    'responsive' => true,
                                  // 'hover' => true,
                                    
                                  
                                    'pjax' => true,
                                    'pjaxSettings' => [
                                        'neverTimeout' => true,
                                    ],
                                    'columns' => [
                                       // ['class' => 'yii\grid\SerialColumn'],
                                        
                                        [
                                            'label' => 'วันที่',
                                            'attribute' => 'regdate',
                                           // 'format' => ['decimal', 0]
                                        ],
                                        [
                                            'label' => 'กายภาพ',
                                            'attribute' => 'กายภาพ',
                                           // 'format' => ['decimal', 0]
                                        ],
                                        

                                    ],
                                ]);
                                ?>
                            </div>


                            