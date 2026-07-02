<?php
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
/* @var $this yii\web\View */

$this->title = 'Dashboard';
?>
<?php

$this->registerCss(<<<CSS
/* Container Styling */
.modern-table-container {
    max-width: 1700px;
    margin: 30px auto;
    padding: 25px;
    background: #f8fbfd;
    border: 1px solid #d0dce6;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    font-family: 'Sarabun', sans-serif;
}

/* GridView Styling - Clean Gray-Blue Theme */
.kv-grid-table {
    border: 1px solid #c5d5e0 !important;
    border-radius: 8px;
    overflow: hidden;
    background-color: #ffffff;
}

/* Header - Soft Gray-Blue Gradient */
.kv-grid-table thead {
    background: linear-gradient(180deg, #e8f1f7 0%, #d4e4ed 100%);
}

.kv-grid-table thead th {
    color: #3d5a6c !important;
    font-weight: 600 !important;
    border: 1px solid #c5d5e0 !important;
    border-bottom: 2px solid #a8bfcf !important;
    padding: 12px 10px !important;
    font-size: 14px !important;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    background: linear-gradient(180deg, #e8f1f7 0%, #d4e4ed 100%);
}

/* Body Cells */
.kv-grid-table tbody td {
    border: 1px solid #e1e9ef !important;
    border-left: 1px solid #e8eff4 !important;
    padding: 10px !important;
    font-size: 14px !important;
    color: #2c3e50 !important;
    background-color: #ffffff;
}

/* Alternating Row Colors */
.kv-grid-table tbody tr:nth-child(even) td {
    background-color: #f7fafb;
}

.kv-grid-table tbody tr:nth-child(odd) td {
    background-color: #ffffff;
}

/* Hover Effect */
.kv-grid-table tbody tr:hover td {
    background-color: #e3f2fd !important;
    transition: background-color 0.2s ease;
}

/* Card Styling */
.card {
    border: 1px solid #d5e0e8;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    background-color: #ffffff;
    margin-bottom: 20px;
}

.card:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
}

.card-header {
    font-weight: 600;
    font-size: 15px;
    padding: 12px 15px;
    border-radius: 10px 10px 0 0;
    border-bottom: 2px solid rgba(255, 255, 255, 0.2);
}

.card-body {
    padding: 15px;
    background-color: #ffffff;
}

/* Label Badges */
.label {
    padding: 5px 10px;
    border-radius: 4px;
    font-weight: 600;
    font-size: 13px;
    display: inline-block;
}

.label-success {
    background-color: #28a745;
    color: white;
}

.label-warning {
    background-color: #ffc107;
    color: #333;
}

.label-danger {
    background-color: #dc3545;
    color: white;
}

/* Status Table Styling */
.table-bordered {
    border: 1px solid #c5d5e0 !important;
    border-radius: 6px;
    overflow: hidden;
}

.table-bordered thead {
    background: linear-gradient(180deg, #e8f1f7 0%, #d4e4ed 100%);
}

.table-bordered th {
    background: linear-gradient(180deg, #e8f1f7 0%, #d4e4ed 100%);
    color: #3d5a6c !important;
    font-weight: 600 !important;
    border: 1px solid #c5d5e0 !important;
    padding: 10px 12px !important;
    font-size: 13px !important;
    text-align: left;
}

.table-bordered td {
    border: 1px solid #e1e9ef !important;
    padding: 10px 12px !important;
    font-size: 13px !important;
    color: #2c3e50;
    background-color: #ffffff;
}

.table-striped tbody tr:nth-of-type(odd) {
    background-color: #f7fafb;
}

.table-striped tbody tr:nth-of-type(even) {
    background-color: #ffffff;
}

.table-striped tbody tr:hover {
    background-color: #e3f2fd;
    transition: background-color 0.2s ease;
}

/* Modern Button Group */
.btn-group-modern {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    justify-content: center;
    margin: 25px 0 35px 0;
}

.btn-modern {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 24px;
    border: none;
    border-radius: 10px;
    font-size: 15px;
    font-weight: 600;
    text-transform: uppercase;
    text-decoration: none;
    color: white;
    transition: all 0.3s ease-in-out;
    background-size: 200% auto;
    box-shadow: 0 3px 12px rgba(0, 0, 0, 0.15);
    cursor: pointer;
}

.btn-modern i {
    font-size: 18px;
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

.btn-modern:hover {
    background-position: right center;
    transform: translateY(-2px);
    box-shadow: 0px 6px 18px rgba(0, 0, 0, 0.25);
}

/* Section Spacing */
.section-spacing {
    margin-bottom: 30px;
}
CSS
);
?>

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

<!-- Section 1: Table Count Summary -->
<div class="modern-table-container section-spacing">
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
                        'bordered' => true,
                        'striped' => false,
                        'responsive' => true,
                        'hover' => true,
                        'columns' => [
                            [
                                'attribute' => 'tables',
                                'label' => 'แฟ้ม',
                                'headerOptions' => ['style' => 'text-align: left; width: 60%;'],
                                'contentOptions' => ['style' => 'text-align: left;'],
                            ],
                            [
                                'attribute' => 'amount',
                                'label' => 'จำนวน',
                                'headerOptions' => ['style' => 'text-align: right; width: 40%;'],
                                'contentOptions' => ['style' => 'text-align: right; font-weight: 500;'],
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
                        'bordered' => true,
                        'striped' => false,
                        'responsive' => true,
                        'hover' => true,
                        'columns' => [
                            [
                                'attribute' => 'tables',
                                'label' => 'แฟ้ม',
                                'headerOptions' => ['style' => 'text-align: left; width: 60%;'],
                                'contentOptions' => ['style' => 'text-align: left;'],
                            ],
                            [
                                'attribute' => 'amount',
                                'label' => 'จำนวน',
                                'headerOptions' => ['style' => 'text-align: right; width: 40%;'],
                                'contentOptions' => ['style' => 'text-align: right; font-weight: 500;'],
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
                        'bordered' => true,
                        'striped' => false,
                        'responsive' => true,
                        'hover' => true,
                        'columns' => [
                            [
                                'attribute' => 'tables',
                                'label' => 'แฟ้ม',
                                'headerOptions' => ['style' => 'text-align: left; width: 60%;'],
                                'contentOptions' => ['style' => 'text-align: left;'],
                            ],
                            [
                                'attribute' => 'amount',
                                'label' => 'จำนวน',
                                'headerOptions' => ['style' => 'text-align: right; width: 40%;'],
                                'contentOptions' => ['style' => 'text-align: right; font-weight: 500;'],
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
                        'bordered' => true,
                        'striped' => false,
                        'responsive' => true,
                        'hover' => true,
                        'columns' => [
                            [
                                'attribute' => 'tables',
                                'label' => 'แฟ้ม',
                                'headerOptions' => ['style' => 'text-align: left; width: 60%;'],
                                'contentOptions' => ['style' => 'text-align: left;'],
                            ],
                            [
                                'attribute' => 'amount',
                                'label' => 'จำนวน',
                                'headerOptions' => ['style' => 'text-align: right; width: 40%;'],
                                'contentOptions' => ['style' => 'text-align: right; font-weight: 500;'],
                                'format' => ['decimal', 0],
                            ],
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Section 2: Table Details with Size Information -->
<div class="modern-table-container section-spacing">
    <div class="row">
        <!-- Card 1 -->
        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    SLAVE Mariadb10-200.70
                </div>
                <div class="card-body">
                    <?= GridView::widget([
                        'dataProvider' => $mbase70Provider,
                        'summary' => '',
                        'bordered' => true,
                        'striped' => false,
                        'responsive' => true,
                        'hover' => true,
                        'columns' => [
                            [
                                'attribute' => 'table_name',
                                'label' => 'Table Name',
                                'format' => 'text',
                                'headerOptions' => ['style' => 'text-align: left;'],
                                'contentOptions' => ['style' => 'text-align: left; font-family: monospace; font-size: 12px;'],
                            ],
                            [
                                'attribute' => 'table_rows',
                                'label' => 'Rows',
                                'format' => ['integer'],
                                'headerOptions' => ['style' => 'text-align: right;'],
                                'contentOptions' => ['style' => 'text-align: right; font-weight: 500;'],
                            ],
                            [
                                'attribute' => 'total_size_gb',
                                'label' => 'Size (GB)',
                                'format' => 'raw',
                                'headerOptions' => ['style' => 'text-align: center;'],
                                'contentOptions' => ['style' => 'text-align: center;'],
                                'value' => function ($model) {
                                    $gb = $model['total_size_gb'];
                                    if ($gb > 10) {
                                        $color = 'label-danger';
                                    } elseif ($gb > 1) {
                                        $color = 'label-warning';
                                    } else {
                                        $color = 'label-success';
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
                        'bordered' => true,
                        'striped' => false,
                        'responsive' => true,
                        'hover' => true,
                        'columns' => [
                            [
                                'attribute' => 'table_name',
                                'label' => 'Table Name',
                                'format' => 'text',
                                'headerOptions' => ['style' => 'text-align: left;'],
                                'contentOptions' => ['style' => 'text-align: left; font-family: monospace; font-size: 12px;'],
                            ],
                            [
                                'attribute' => 'table_rows',
                                'label' => 'Rows',
                                'format' => ['integer'],
                                'headerOptions' => ['style' => 'text-align: right;'],
                                'contentOptions' => ['style' => 'text-align: right; font-weight: 500;'],
                            ],
                            [
                                'attribute' => 'total_size_gb',
                                'label' => 'Size (GB)',
                                'format' => 'raw',
                                'headerOptions' => ['style' => 'text-align: center;'],
                                'contentOptions' => ['style' => 'text-align: center;'],
                                'value' => function ($model) {
                                    $gb = $model['total_size_gb'];
                                    if ($gb > 10) {
                                        $color = 'label-danger';
                                    } elseif ($gb > 1) {
                                        $color = 'label-warning';
                                    } else {
                                        $color = 'label-success';
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
                        'bordered' => true,
                        'striped' => false,
                        'responsive' => true,
                        'hover' => true,
                        'columns' => [
                            [
                                'attribute' => 'table_name',
                                'label' => 'Table Name',
                                'format' => 'text',
                                'headerOptions' => ['style' => 'text-align: left;'],
                                'contentOptions' => ['style' => 'text-align: left; font-family: monospace; font-size: 12px;'],
                            ],
                            [
                                'attribute' => 'table_rows',
                                'label' => 'Rows',
                                'format' => ['integer'],
                                'headerOptions' => ['style' => 'text-align: right;'],
                                'contentOptions' => ['style' => 'text-align: right; font-weight: 500;'],
                            ],
                            [
                                'attribute' => 'total_size_gb',
                                'label' => 'Size (GB)',
                                'format' => 'raw',
                                'headerOptions' => ['style' => 'text-align: center;'],
                                'contentOptions' => ['style' => 'text-align: center;'],
                                'value' => function ($model) {
                                    $gb = $model['total_size_gb'];
                                    if ($gb > 10) {
                                        $color = 'label-danger';
                                    } elseif ($gb > 1) {
                                        $color = 'label-warning';
                                    } else {
                                        $color = 'label-success';
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
                        'dataProvider' => $mbase4Provider,
                        'summary' => '',
                        'bordered' => true,
                        'striped' => false,
                        'responsive' => true,
                        'hover' => true,
                        'columns' => [
                            [
                                'attribute' => 'table_name',
                                'label' => 'Table Name',
                                'format' => 'text',
                                'headerOptions' => ['style' => 'text-align: left;'],
                                'contentOptions' => ['style' => 'text-align: left; font-family: monospace; font-size: 12px;'],
                            ],
                            [
                                'attribute' => 'table_rows',
                                'label' => 'Rows',
                                'format' => ['integer'],
                                'headerOptions' => ['style' => 'text-align: right;'],
                                'contentOptions' => ['style' => 'text-align: right; font-weight: 500;'],
                            ],
                            [
                                'attribute' => 'total_size_gb',
                                'label' => 'Size (GB)',
                                'format' => 'raw',
                                'headerOptions' => ['style' => 'text-align: center;'],
                                'contentOptions' => ['style' => 'text-align: center;'],
                                'value' => function ($model) {
                                    $gb = $model['total_size_gb'];
                                    if ($gb > 10) {
                                        $color = 'label-danger';
                                    } elseif ($gb > 1) {
                                        $color = 'label-warning';
                                    } else {
                                        $color = 'label-success';
                                    }
                                    return '<span class="label ' . $color . '">' . number_format($gb, 3) . '</span>';
                                },
                            ],
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Section 3: Slave Replication Status -->
<div class="modern-table-container">
    <div class="row">
        <!-- Card 1 -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-info text-white">
                    SLAVE Percona Mysql5.7-200.74
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th style="width: 45%;">Slave IO State</th>
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
                            <td><strong><?= Html::encode($slaveStatus4['Slave_IO_Running']) ?></strong></td>
                        </tr>
                        <tr>
                            <th>Slave SQL Running</th>
                            <td><strong><?= Html::encode($slaveStatus4['Slave_SQL_Running']) ?></strong></td>
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
                            <td><strong><?= Html::encode($slaveStatus4['Seconds_Behind_Master']) ?></strong></td>
                        </tr>
                        <tr>
                            <th>Last IO Error</th>
                            <td style="color: #d32f2f; font-size: 12px;"><?= Html::encode($slaveStatus4['Last_IO_Error']) ?></td>
                        </tr>
                        <tr>
                            <th>Last SQL Error</th>
                            <td style="color: #d32f2f; font-size: 12px;"><?= Html::encode($slaveStatus4['Last_SQL_Error']) ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    SLAVE Mariadb10.1 200.70
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th style="width: 45%;">Slave IO State</th>
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
                            <td><strong><?= Html::encode($slaveStatus74['Slave_IO_Running']) ?></strong></td>
                        </tr>
                        <tr>
                            <th>Slave SQL Running</th>
                            <td><strong><?= Html::encode($slaveStatus74['Slave_SQL_Running']) ?></strong></td>
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
                            <td><strong><?= Html::encode($slaveStatus74['Seconds_Behind_Master']) ?></strong></td>
                        </tr>
                        <tr>
                            <th>Last IO Error</th>
                            <td style="color: #d32f2f; font-size: 12px;"><?= Html::encode($slaveStatus74['Last_IO_Error']) ?></td>
                        </tr>
                        <tr>
                            <th>Last SQL Error</th>
                            <td style="color: #d32f2f; font-size: 12px;"><?= Html::encode($slaveStatus74['Last_SQL_Error']) ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-warning text-white">
                    SLAVE Percona200.4
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th style="width: 45%;">Slave IO State</th>
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
                            <td><strong><?= Html::encode($slaveStatus74['Slave_IO_Running']) ?></strong></td>
                        </tr>
                        <tr>
                            <th>Slave SQL Running</th>
                            <td><strong><?= Html::encode($slaveStatus74['Slave_SQL_Running']) ?></strong></td>
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
                            <td><strong><?= Html::encode($slaveStatus74['Seconds_Behind_Master']) ?></strong></td>
                        </tr>
                        <tr>
                            <th>Last IO Error</th>
                            <td style="color: #d32f2f; font-size: 12px;"><?= Html::encode($slaveStatus74['Last_IO_Error']) ?></td>
                        </tr>
                        <tr>
                            <th>Last SQL Error</th>
                            <td style="color: #d32f2f; font-size: 12px;"><?= Html::encode($slaveStatus74['Last_SQL_Error']) ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
