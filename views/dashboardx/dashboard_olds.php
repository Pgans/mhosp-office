<?php
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
/* @var $this yii\web\View */

$this->title = 'Dashboard';
?>
<style>
    /* Global Styles */
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f5f7;
        color: #333;
    }
    .container-fluid {
        padding: 20px;
    }
    .card {
        border-radius: 12px;
        border: none;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        background-color: #ffffff;
    }
    .card-header {
        font-weight: bold;
        font-size: 16px;
        padding: 12px 16px;
        border-bottom: 1px solid #e0e0e0;
    }
    .card-header.bg-primary {
        background-color: #007bff;
        color: #fff;
    }
    .card-header.bg-success {
        background-color: #28a745;
        color: #fff;
    }
    .card-header.bg-warning {
        background-color: #ffc107;
        color: #fff;
    }
    .card-header.bg-danger {
        background-color: #dc3545;
        color: #fff;
    }
    .card-body {
        padding: 16px;
    }

    /* GridView Styles */
    .grid-view table {
        border-collapse: collapse;
        width: 100%;
        border-radius: 8px;
        overflow: hidden;
    }
    .grid-view table th, .grid-view table td {
        padding: 12px 8px;
        font-size: 14px;
        color: #333;
    }
    .grid-view table thead th {
        background-color: #f1f3f5;
        text-align: left;
        font-weight: bold;
        border-bottom: 2px solid #ddd;
    }
    .grid-view table tbody tr:nth-child(odd) {
        background-color: #f8f9fa;
    }
    .grid-view table tbody tr:nth-child(even) {
        background-color: #ffffff;
    }
    .grid-view table tbody tr:hover {
        background-color: #e9f5ff;
    }
</style>
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

    .btn-slave2 {
        background: linear-gradient(135deg, #0ba360, #3cba92);
    }

    .btn-slave70 {
        background: linear-gradient(135deg, #ff512f, #dd2476);
    }
	.btn-rep {
        background: linear-gradient(135deg, #18abab, #41f2f2
	);
	
    }
    /* Hover Effect */
    .btn-modern:hover {
        transform: translateY(-3px);
        box-shadow: 0px 6px 15px rgba(0, 0, 0, 0.3);
    }
</style>

<div class="btn-group-modern">
    <a href="<?= Url::to(['/process/index']) ?>" class="btn-modern btn-samba">
        <i class="fa fa-check-square-o"></i> Samba
    </a>
    <a href="<?= Url::to(['/process2/index']) ?>" class="btn-modern btn-slave2">
        <i class="fa fa-check-square-o"></i> Slave2
    </a>
    <a href="<?= Url::to(['/process70/index']) ?>" class="btn-modern btn-slave70">
        <i class="fa fa-check-square-o"></i> Slave70
    </a>
	 <a href="<?= Url::to(['/dashboardx/dashboard']) ?>" class="btn-modern btn-rep">
        <i class="fa fa-check-square-o"></i> Replication
    </a>
	 <a href="<?= Url::to(['/dashboardlock/index']) ?>" class="btn-modern btn-rep">
        <i class="fa fa-check-square-o"></i> Lock
    </a>
</div>

<div class="container-fluid">
    <div class="row">
        <!-- Card 1 -->
        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    SLAVE Mariadb10-200.2
                </div>
                <div class="card-body">
                    <?= GridView::widget([
                        'dataProvider' => $data14Provider,
                        'summary' => '',
                        'bordered' => false,
                        'striped' => false,
                        'responsive' => true,
                        'hover' => true,
                        'tableOptions' => ['class' => 'table'],
                        'columns' => [
                            [
                                'attribute' => 'tables',
                                'label' => 'แฟ้ม',
                                'headerOptions' => ['style' => 'text-align: left;'],
                                'contentOptions' => ['style' => 'text-align: left;'],
                            ],
                            [
                                'attribute' => 'amount',
                                'label' => 'จำนวน',
                                'headerOptions' => ['style' => 'text-align: right;'],
                                'contentOptions' => ['style' => 'text-align: right;'],
                                'format' => ['decimal', 0],
                            ],
                        ],
                    ]); ?>
                </div>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-header bg-success text-white">
                    SLAVE Mariadb10-200.70
                </div>
                <div class="card-body">
                    <?= GridView::widget([
                        'dataProvider' => $data70Provider,
                        'summary' => '',
                        'bordered' => false,
                        'striped' => false,
                        'responsive' => true,
                        'hover' => true,
                        'tableOptions' => ['class' => 'table'],
                        'columns' => [
                            [
                                'attribute' => 'tables',
                                'label' => 'แฟ้ม',
                                'headerOptions' => ['style' => 'text-align: left;'],
                                'contentOptions' => ['style' => 'text-align: left;'],
                            ],
                            [
                                'attribute' => 'amount',
                                'label' => 'จำนวน',
                                'headerOptions' => ['style' => 'text-align: right;'],
                                'contentOptions' => ['style' => 'text-align: right;'],
                                'format' => ['decimal', 0],
                            ],
                        ],
                    ]); ?>
                </div>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-header bg-warning text-white">
                    SLAVE Percona200.4 
                </div>
                <div class="card-body">
                    <?= GridView::widget([
                        'dataProvider' => $data4Provider,
                        'summary' => '',
                        'bordered' => false,
                        'striped' => false,
                        'responsive' => true,
                        'hover' => true,
                        'tableOptions' => ['class' => 'table'],
                        'columns' => [
                            [
                                'attribute' => 'tables',
                                'label' => 'แฟ้ม',
                                'headerOptions' => ['style' => 'text-align: left;'],
                                'contentOptions' => ['style' => 'text-align: left;'],
                            ],
                            [
                                'attribute' => 'amount',
                                'label' => 'จำนวน',
                                'headerOptions' => ['style' => 'text-align: right;'],
                                'contentOptions' => ['style' => 'text-align: right;'],
                                'format' => ['decimal', 0],
                            ],
                        ],
                    ]); ?>
                </div>
            </div>
        </div>

        <!-- Card 4 -->
        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    MASTER Mysql200.7
                </div>
                <div class="card-body">
                    <?= GridView::widget([
                        'dataProvider' => $data7Provider,
                        'summary' => '',
                        'bordered' => false,
                        'striped' => false,
                        'responsive' => true,
                        'hover' => true,
                        'tableOptions' => ['class' => 'table'],
                        'columns' => [
                            [
                                'attribute' => 'tables',
                                'label' => 'แฟ้ม',
                                'headerOptions' => ['style' => 'text-align: left;'],
                                'contentOptions' => ['style' => 'text-align: left;'],
                            ],
                            [
                                'attribute' => 'amount',
                                'label' => 'จำนวน',
                                'headerOptions' => ['style' => 'text-align: right;'],
                                'contentOptions' => ['style' => 'text-align: right;'],
                                'format' => ['decimal', 0],
                            ],
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <!-- Card 1 นับจำนวน Records  ######################################################################## -->
        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    SLAVE Mariadb10-200.2
                </div>
                <div class="card-body">
                    <?= GridView::widget([
                        'dataProvider' => $mbase14Provider,
                        'summary' => '',
                        'bordered' => false,
                        'striped' => false,
                        'responsive' => true,
                        'hover' => true,
                        'tableOptions' => ['class' => 'table'],
                        'columns' => [
                            [
							'attribute' => 'table_name',
							'label' => 'Table Name',
							'format' => 'text',
						],
						[
							'attribute' => 'table_rows',
							'label' => 'Rows',
							'format' => ['integer'],
						],
						/*
						[
							'attribute' => 'total_size',
							'label' => 'Total(Bytes)',
							'format' => ['decimal', 0],
						],
						/*
						[
							'attribute' => 'total_size_mb',
							'label' => 'Total Size (MB)',
							'format' => ['decimal', 2],
						],
						*/
						[
						'attribute' => 'total_size_gb',
						'label' => 'Total (GB)',
						'format' => 'raw', // ต้องใช้ 'raw' เพื่อให้ html ทำงาน
						'value' => function ($model) {
							$gb = $model['total_size_gb'];

							// กำหนดสีตามขนาด
							if ($gb > 10) {
								$color = 'label-danger'; // GB ใหญ่ > 10 GB สีแดง
							} elseif ($gb > 1) {
								$color = 'label-warning'; // 1-10 GB สีส้ม
							} else {
								$color = 'label-success'; // น้อยกว่า 1 GB สีเขียว
							}

							return '<span class="label ' . $color . '">' . number_format($gb, 3) . '</span>';
						},
					],

                        ],
                    ]); ?>
                </div>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-header bg-success text-white">
                    SLAVE Mariadb10-200.70
                </div>
                <div class="card-body">
                    <?= GridView::widget([
                        'dataProvider' => $mbase70Provider,
                        'summary' => '',
                        'bordered' => false,
                        'striped' => false,
                        'responsive' => true,
                        'hover' => true,
                        'tableOptions' => ['class' => 'table'],
                        'columns' => [
                            [
							'attribute' => 'table_name',
							'label' => 'Table Name',
							'format' => 'text',
						],
						[
							'attribute' => 'table_rows',
							'label' => 'Rows',
							'format' => ['integer'],
						],
						/*
						[
							'attribute' => 'total_size',
							'label' => 'Total(Bytes)',
							'format' => ['decimal', 0],
						],
						/*
						[
							'attribute' => 'total_size_mb',
							'label' => 'Total Size (MB)',
							'format' => ['decimal', 2],
						],
						*/
						[
						'attribute' => 'total_size_gb',
						'label' => 'Total (GB)',
						'format' => 'raw', // ต้องใช้ 'raw' เพื่อให้ html ทำงาน
						'value' => function ($model) {
							$gb = $model['total_size_gb'];

							// กำหนดสีตามขนาด
							if ($gb > 10) {
								$color = 'label-danger'; // GB ใหญ่ > 10 GB สีแดง
							} elseif ($gb > 1) {
								$color = 'label-warning'; // 1-10 GB สีส้ม
							} else {
								$color = 'label-success'; // น้อยกว่า 1 GB สีเขียว
							}

							return '<span class="label ' . $color . '">' . number_format($gb, 3) . '</span>';
						},
					],

                        ],
                    ]); ?>
                </div>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-header bg-warning text-white">
                    SLAVE Percona200.4 
                </div>
                <div class="card-body">
                    <?= GridView::widget([
                        'dataProvider' => $mbase4Provider,
                        'summary' => '',
                        'bordered' => false,
                        'striped' => false,
                        'responsive' => true,
                        'hover' => true,
                        'tableOptions' => ['class' => 'table'],
                        'columns' => [
                            [
							'attribute' => 'table_name',
							'label' => 'Table Name',
							'format' => 'text',
						],
						
						[
							'attribute' => 'table_rows',
							'label' => 'Rows',
							'format' => ['integer'],
						],
						/*
						[
							'attribute' => 'total_size',
							'label' => 'Total(Bytes)',
							'format' => ['decimal', 0],
						],
						/*
						[
							'attribute' => 'total_size_mb',
							'label' => 'Total Size (MB)',
							'format' => ['decimal', 2],
						],
						*/
						[
						'attribute' => 'total_size_gb',
						'label' => 'Total (GB)',
						'format' => 'raw', // ต้องใช้ 'raw' เพื่อให้ html ทำงาน
						'value' => function ($model) {
							$gb = $model['total_size_gb'];

							// กำหนดสีตามขนาด
							if ($gb > 10) {
								$color = 'label-danger'; // GB ใหญ่ > 10 GB สีแดง
							} elseif ($gb > 1) {
								$color = 'label-warning'; // 1-10 GB สีส้ม
							} else {
								$color = 'label-success'; // น้อยกว่า 1 GB สีเขียว
							}

							return '<span class="label ' . $color . '">' . number_format($gb, 3) . '</span>';
						},
					],

                        ],
                    ]); ?>
                </div>
            </div>
        </div>

        <!-- Card 4 -->
        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    MASTER Mysql200.7
                </div>
                <div class="card-body">
                    <?= GridView::widget([
                        'dataProvider' => $data7Provider,
                        'summary' => '',
                        'bordered' => false,
                        'striped' => false,
                        'responsive' => true,
                        'hover' => true,
                        'tableOptions' => ['class' => 'table'],
                        'columns' => [
                            [
                                'attribute' => 'tables',
                                'label' => 'แฟ้ม',
                                'headerOptions' => ['style' => 'text-align: left;'],
                                'contentOptions' => ['style' => 'text-align: left;'],
                            ],
                            [
                                'attribute' => 'amount',
                                'label' => 'จำนวน',
                                'headerOptions' => ['style' => 'text-align: right;'],
                                'contentOptions' => ['style' => 'text-align: right;'],
                                'format' => ['decimal', 0],
                            ],
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <!-- Card 1 -->
        <div class="col-md-4 ">
            <div class="card">
                <div class="card-header bg-default text-green">
                   SLAVE-200.2-Mariadb10.1
                </div>
                 <div class="card-body">
                    <table class="table table-bordered table-striped">
                       <th>Slave IO State</th>
                        <td><?= Html::encode($slaveStatus2['Slave_IO_State']) ?></td>
                    </tr>
                    <tr>
                        <th>Master Host</th>
                        <td><?= Html::encode($slaveStatus2['Master_Host']) ?></td>
                    </tr>
                    <tr>
                        <th>Master User</th>
                        <td><?= Html::encode($slaveStatus2['Master_User']) ?></td>
                    </tr>
                    <tr>
                        <th>Master Log File</th>
                        <td><?= Html::encode($slaveStatus2['Master_Log_File']) ?></td>
                    </tr>
                    <tr>
                        <th>Slave IO Running</th>
                        <td><?= Html::encode($slaveStatus2['Slave_IO_Running']) ?></td>
                    </tr>
                    <tr>
                        <th>Slave SQL Running</th>
                        <td><?= Html::encode($slaveStatus2['Slave_SQL_Running']) ?></td>
                    </tr>
                    <tr>
                        <th>Relay Log File</th>
                        <td><?= Html::encode($slaveStatus2['Relay_Log_File']) ?></td>
                    </tr>
                    <tr>
                        <th>Relay Log Position</th>
                        <td><?= Html::encode($slaveStatus2['Relay_Log_Pos']) ?></td>
                    </tr>
                    <tr>
                        <th>Exec Master Log Pos</th>
                        <td><?= Html::encode($slaveStatus2['Exec_Master_Log_Pos']) ?></td>
                    </tr>
                    <tr>
                        <th>Seconds Behind Master</th>
                        <td><?= Html::encode($slaveStatus2['Seconds_Behind_Master']) ?></td>
                    </tr>
                    <tr>
                        <th>Last IO Error</th>
                        <td><?= Html::encode($slaveStatus2['Last_IO_Error']) ?></td>
                    </tr>
                    <tr>
                        <th>Last SQL Error</th>
                        <td><?= Html::encode($slaveStatus2['Last_SQL_Error']) ?></td>
                    </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-defualt text-primary">
                    SLAVE Mariadb10-200.70
                </div>
               <div class="card-body">
                    <table class="table table-bordered table-striped">
                       <th>Slave IO State</th>
                        <td><?= Html::encode($slaveStatus70['Slave_IO_State']) ?></td>
                    </tr>
                    <tr>
                        <th>Master Host</th>
                        <td><?= Html::encode($slaveStatus70['Master_Host']) ?></td>
                    </tr>
                    <tr>
                        <th>Master User</th>
                        <td><?= Html::encode($slaveStatus70['Master_User']) ?></td>
                    </tr>
                    <tr>
                        <th>Master Log File</th>
                        <td><?= Html::encode($slaveStatus70['Master_Log_File']) ?></td>
                    </tr>
                    <tr>
                        <th>Slave IO Running</th>
                        <td><?= Html::encode($slaveStatus70['Slave_IO_Running']) ?></td>
                    </tr>
                    <tr>
                        <th>Slave SQL Running</th>
                        <td><?= Html::encode($slaveStatus70['Slave_SQL_Running']) ?></td>
                    </tr>
                    <tr>
                        <th>Relay Log File</th>
                        <td><?= Html::encode($slaveStatus70['Relay_Log_File']) ?></td>
                    </tr>
                    <tr>
                        <th>Relay Log Position</th>
                        <td><?= Html::encode($slaveStatus70['Relay_Log_Pos']) ?></td>
                    </tr>
                    <tr>
                        <th>Exec Master Log Pos</th>
                        <td><?= Html::encode($slaveStatus70['Exec_Master_Log_Pos']) ?></td>
                    </tr>
                    <tr>
                        <th>Seconds Behind Master</th>
                        <td><?= Html::encode($slaveStatus70['Seconds_Behind_Master']) ?></td>
                    </tr>
                    <tr>
                        <th>Last IO Error</th>
                        <td><?= Html::encode($slaveStatus70['Last_IO_Error']) ?></td>
                    </tr>
                    <tr>
                        <th>Last SQL Error</th>
                        <td><?= Html::encode($slaveStatus70['Last_SQL_Error']) ?></td>
                    </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-defualt text-purple">
                    SLAVE Percona200.4 
                </div>
               <div class="card-body">
                    <table class="table table-bordered table-striped">
                       <th>Slave IO State</th>
                        <td><?= Html::encode($slaveStatus4['Slave_IO_State']) ?></td>
                    </tr>
                    <tr>
                        <th>Master Host</th>
                        <td><?= Html::encode($slaveStatus4['Master_Host']) ?></td>
                    </tr>
                    <tr>
                        <th>Master User</th>
                        <td><?= Html::encode($slaveStatus4['Master_User']) ?></td>
                    </tr>
                    <tr>
                        <th>Master Log File</th>
                        <td><?= Html::encode($slaveStatus4['Master_Log_File']) ?></td>
                    </tr>
                    <tr>
                        <th>Slave IO Running</th>
                        <td><?= Html::encode($slaveStatus4['Slave_IO_Running']) ?></td>
                    </tr>
                    <tr>
                        <th>Slave SQL Running</th>
                        <td><?= Html::encode($slaveStatus4['Slave_SQL_Running']) ?></td>
                    </tr>
                    <tr>
                        <th>Relay Log File</th>
                        <td><?= Html::encode($slaveStatus4['Relay_Log_File']) ?></td>
                    </tr>
                    <tr>
                        <th>Relay Log Position</th>
                        <td><?= Html::encode($slaveStatus4['Relay_Log_Pos']) ?></td>
                    </tr>
                    <tr>
                        <th>Exec Master Log Pos</th>
                        <td><?= Html::encode($slaveStatus4['Exec_Master_Log_Pos']) ?></td>
                    </tr>
                    <tr>
                        <th>Seconds Behind Master</th>
                        <td><?= Html::encode($slaveStatus4['Seconds_Behind_Master']) ?></td>
                    </tr>
                    <tr>
                        <th>Last IO Error</th>
                        <td><?= Html::encode($slaveStatus4['Last_IO_Error']) ?></td>
                    </tr>
                    <tr>
                        <th>Last SQL Error</th>
                        <td><?= Html::encode($slaveStatus4['Last_SQL_Error']) ?></td>
                    </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

 