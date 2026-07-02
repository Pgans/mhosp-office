<?php
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
/* @var $this yii\web\View */

$this->title = 'Dashboard';
?>
<?php


$this->registerCss(<<<CSS
.modern-table-container {
    max-width: 1700px;  /* ปรับให้กว้างขึ้น */
    margin: 30px auto;
    padding: 25px;
    background: #f0faff;
    border: 1px solid #b3e5fc;
    border-radius: 15px;
    box-shadow: 0 8px 24px rgba(0, 123, 255, 0.15);
    font-family: 'Sarabun', sans-serif;
}

.modern-table-container h4 {
    color: #007acc;
    text-align: center;
    margin-bottom: 20px;
    font-size: 24px;
    font-weight: bold;
    text-shadow: 1px 1px 0px #fff;
}

.modern-table {
    width: 100%;
    border-collapse: collapse;
    background-color: #ffffff;
    color: #333;
}

.modern-table thead {
    background-color: #0288d1;
    color: #ffffff;
    font-weight: bold;
}

.modern-table th, .modern-table td {
    border: 1px solid #dee2e6;
    padding: 10px 12px;
    text-align: center;
    font-size: 15px;
}

.modern-table tbody tr:hover {
    background-color: #e0f7fa;
    transition: background-color 0.3s ease;
}

.modern-table tbody td {
    color: #004d40;
}
CSS
);
?>
<style>
    /* Modern Button Group */
    .btn-group-modern {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        justify-content: center;
        margin-top: 20px;
    }

    .btn-modern {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 14px 24px;
        border: none;
        border-radius: 12px;
        font-size: 16px;
        font-weight: bold;
        text-transform: uppercase;
        text-decoration: none;
        color: white;
        transition: all 0.3s ease-in-out;
        background-size: 200% auto;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        cursor: pointer;
    }

    .btn-modern i {
        font-size: 20px;
    }

    .btn-samba {
        background-image: linear-gradient(to right, #6a11cb 0%, #2575fc 51%, #6a11cb 100%);
    }

    .btn-slave2 {
        background-image: linear-gradient(to right, #0ba360 0%, #3cba92 51%, #0ba360 100%);
    }

    .btn-slave70 {
        background-image: linear-gradient(to right, #ff512f 0%, #dd2476 51%, #ff512f 100%);
    }

    .btn-rep {
        background-image: linear-gradient(to right, #18abab 0%, #41f2f2 51%, #18abab 100%);
    }

    /* Button Hover */
    .btn-modern:hover {
        background-position: right center;
        transform: scale(1.05);
        box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.3);
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
<!--<div class="container-fluid">-->
<div class="modern-table-container">
    <div class="row">
        <!-- Card 1 -->
        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
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

        <!-- Card 2 -->
        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-header bg-success text-white">
                    SLAVE Mariadb10-200.74
                </div>
                <div class="card-body">
                    <?= GridView::widget([
                        'dataProvider' => $data74Provider,
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

<div class="modern-table-container">
    <div class="row">
        <!-- Card 1 นับจำนวน Records  ######################################################################## -->
        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    SLAVE Mariadb10-200.70
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
                    SLAVE Mariadb10-200.74
                </div>
                <div class="card-body">
                    <?= GridView::widget([
                        'dataProvider' => $mbase74Provider,
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

<!--<div class="container-fluid">-->
<div class="modern-table-container">
    <div class="row">
        <!-- Card 1 -->
        <div class="col-md-4 ">
            <div class="card">
                <div class="card-header bg-default text-green">
                   SLAVE Peconar Mysql5.7-200.74
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

        <!-- Card 2 -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-defualt text-primary">
                    SLAVE Mariadb10.1 200.70
                </div>
               <div class="card-body">
                    <table class="table table-bordered table-striped">
                       <th>Slave IO State</th>
                        <td><?= Html::encode($slaveStatus74['Slave_IO_State']) ?></td>
                    </tr>
                    <tr>
                        <th>Master Host</th>
                        <td><?= Html::encode($slaveStatus74['Master_Host']) ?></td>
                    </tr>
                    <tr>
                        <th>Master User</th>
                        <td><?= Html::encode($slaveStatus74['Master_User']) ?></td>
                    </tr>
                    <tr>
                        <th>Master Log File</th>
                        <td><?= Html::encode($slaveStatus74['Master_Log_File']) ?></td>
                    </tr>
                    <tr>
                        <th>Slave IO Running</th>
                        <td><?= Html::encode($slaveStatus74['Slave_IO_Running']) ?></td>
                    </tr>
                    <tr>
                        <th>Slave SQL Running</th>
                        <td><?= Html::encode($slaveStatus74['Slave_SQL_Running']) ?></td>
                    </tr>
                    <tr>
                        <th>Relay Log File</th>
                        <td><?= Html::encode($slaveStatus74['Relay_Log_File']) ?></td>
                    </tr>
                    <tr>
                        <th>Relay Log Position</th>
                        <td><?= Html::encode($slaveStatus74['Relay_Log_Pos']) ?></td>
                    </tr>
                    <tr>
                        <th>Exec Master Log Pos</th>
                        <td><?= Html::encode($slaveStatus74['Exec_Master_Log_Pos']) ?></td>
                    </tr>
                    <tr>
                        <th>Seconds Behind Master</th>
                        <td><?= Html::encode($slaveStatus74['Seconds_Behind_Master']) ?></td>
                    </tr>
                    <tr>
                        <th>Last IO Error</th>
                        <td><?= Html::encode($slaveStatus74['Last_IO_Error']) ?></td>
                    </tr>
                    <tr>
                        <th>Last SQL Error</th>
                        <td><?= Html::encode($slaveStatus74['Last_SQL_Error']) ?></td>
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

 