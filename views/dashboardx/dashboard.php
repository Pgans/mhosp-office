<?php
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
/* @var $this yii\web\View */

$this->title = 'Dashboard';
?>
<?php

$this->registerCss(<<<CSS
/* Import Modern Font */
@import url('https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;600;700&display=swap');

/* Global Styles */
body {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    font-family: 'Sarabun', sans-serif;
}

/* Container Styling - Canva Inspired */
.modern-table-container {
    max-width: 1700px;
    margin: 30px auto;
    padding: 30px;
    background: #ffffff;
    border-radius: 20px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
    font-family: 'Sarabun', sans-serif;
}

/* Section Title */
.section-title {
    font-size: 28px;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 25px;
    text-align: center;
    position: relative;
    padding-bottom: 15px;
}

.section-title:after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 80px;
    height: 4px;
    background: linear-gradient(to right, #667eea, #764ba2);
    border-radius: 2px;
}

/* GridView Styling - Modern Canva Style */
.kv-grid-table {
    border: none !important;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
}

/* Header - Modern Gradient */
.kv-grid-table thead {
    background: linear-gradient(135deg, #6B73FF 0%, #000DFF 100%);
}

.kv-grid-table thead th {
    color: #ffffff !important;
    font-weight: 600 !important;
    border: none !important;
    padding: 15px 12px !important;
    font-size: 14px !important;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Body Cells - Clean Modern */
.kv-grid-table tbody td {
    border: none !important;
    border-bottom: 1px solid #f0f0f0 !important;
    padding: 14px 12px !important;
    font-size: 14px !important;
    color: #2d3748 !important;
    background-color: #ffffff;
}

/* Alternating Row Colors - Subtle */
.kv-grid-table tbody tr:nth-child(even) td {
    background-color: #f8f9fa;
}

.kv-grid-table tbody tr:nth-child(odd) td {
    background-color: #ffffff;
}

/* Hover Effect - Smooth */
.kv-grid-table tbody tr:hover td {
    background-color: #e8f4ff !important;
    transform: scale(1.01);
    transition: all 0.2s ease;
}

/* Card Styling - Canva Premium Look */
.card {
    border: none;
    border-radius: 16px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    background-color: #ffffff;
    margin-bottom: 25px;
    overflow: hidden;
}

.card:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
}

.card-header {
    font-weight: 700;
    font-size: 16px;
    padding: 16px 20px;
    border: none;
    position: relative;
}

.card-header:before {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: rgba(255, 255, 255, 0.3);
}

.card-body {
    padding: 20px;
    background-color: #ffffff;
}

/* Enhanced Card Headers */
.card-header.bg-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.card-header.bg-success {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
}

.card-header.bg-warning {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.card-header.bg-danger {
    background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
}

.card-header.bg-info {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
}

/* Label Badges - Modern Pills */
.label {
    padding: 6px 14px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 12px;
    display: inline-block;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.label-success {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    color: white;
    box-shadow: 0 4px 10px rgba(17, 153, 142, 0.3);
}

.label-warning {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: white;
    box-shadow: 0 4px 10px rgba(240, 147, 251, 0.3);
}

.label-danger {
    background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
    color: white;
    box-shadow: 0 4px 10px rgba(250, 112, 154, 0.3);
}

/* Status Table Styling - Premium */
.table-bordered {
    border: none !important;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
}

.table-bordered thead {
    background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
}

.table-bordered th {
    background: transparent;
    color: #2d3748 !important;
    font-weight: 600 !important;
    border: none !important;
    border-bottom: 2px solid #e0e0e0 !important;
    padding: 12px 15px !important;
    font-size: 13px !important;
    text-align: left;
}

.table-bordered td {
    border: none !important;
    border-bottom: 1px solid #f0f0f0 !important;
    padding: 12px 15px !important;
    font-size: 13px !important;
    color: #2d3748;
    background-color: #ffffff;
}

.table-striped tbody tr:nth-of-type(odd) {
    background-color: #ffffff;
}

.table-striped tbody tr:nth-of-type(even) {
    background-color: #f8f9fa;
}

.table-striped tbody tr:hover {
    background-color: #e8f4ff;
    transition: background-color 0.2s ease;
}

/* Modern Button Group - Canva Style */
.btn-group-modern {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    justify-content: center;
    margin: 30px 0 40px 0;
}

.btn-modern {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 14px 28px;
    border: none;
    border-radius: 50px;
    font-size: 15px;
    font-weight: 600;
    text-transform: uppercase;
    text-decoration: none;
    color: white;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    background-size: 200% auto;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    cursor: pointer;
    letter-spacing: 0.5px;
}

.btn-modern i {
    font-size: 18px;
}

.btn-samba {
    background: linear-gradient(to right, #667eea 0%, #764ba2 100%);
}

.btn-slave2 {
    background: linear-gradient(to right, #11998e 0%, #38ef7d 100%);
}

.btn-slave70 {
    background: linear-gradient(to right, #f093fb 0%, #f5576c 100%);
}

.btn-rep {
    background: linear-gradient(to right, #4facfe 0%, #00f2fe 100%);
}

.btn-modern:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.25);
}

/* Section Spacing */
.section-spacing {
    margin-bottom: 40px;
}

/* Responsive Grid */
@media (max-width: 768px) {
    .modern-table-container {
        padding: 20px;
    }
    
    .btn-modern {
        padding: 12px 20px;
        font-size: 14px;
    }
}

/* Animation */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.card {
    animation: fadeInUp 0.5s ease-out;
}

/* Monospace Font for Table Names */
.table-name-cell {
    font-family: 'Courier New', monospace;
    font-size: 12px;
    font-weight: 500;
    color: #4a5568;
}

/* Status Emphasis */
.status-value {
    font-weight: 600;
    color: #2d3748;
}

.error-text {
    color: #e53e3e;
    font-size: 12px;
    font-style: italic;
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
    <h4 class="section-title">📊 Database Table Summary</h4>
    <div class="row">
        <!-- Card 1 -->
        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <i class="fa fa-database"></i> SLAVE Mariadb10-200.70
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
                                'contentOptions' => ['style' => 'text-align: right; font-weight: 600;'],
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
                    <i class="fa fa-database"></i> SLAVE Mariadb10-200.74
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
                                'contentOptions' => ['style' => 'text-align: right; font-weight: 600;'],
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
                    <i class="fa fa-database"></i> SLAVE Percona200.4
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
                                'contentOptions' => ['style' => 'text-align: right; font-weight: 600;'],
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
                    <i class="fa fa-database"></i> MASTER Mysql200.7
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
                                'contentOptions' => ['style' => 'text-align: right; font-weight: 600;'],
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
    <h4 class="section-title">💾 Database Storage Analysis</h4>
    <div class="row">
        <!-- Card 1 -->
        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <i class="fa fa-table"></i> SLAVE Mariadb10-200.70
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
                                'contentOptions' => ['class' => 'table-name-cell', 'style' => 'text-align: left;'],
                            ],
                            [
                                'attribute' => 'table_rows',
                                'label' => 'Rows',
                                'format' => ['integer'],
                                'headerOptions' => ['style' => 'text-align: right;'],
                                'contentOptions' => ['style' => 'text-align: right; font-weight: 600;'],
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
                    <i class="fa fa-table"></i> SLAVE Mariadb10-200.74
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
                                'contentOptions' => ['class' => 'table-name-cell', 'style' => 'text-align: left;'],
                            ],
                            [
                                'attribute' => 'table_rows',
                                'label' => 'Rows',
                                'format' => ['integer'],
                                'headerOptions' => ['style' => 'text-align: right;'],
                                'contentOptions' => ['style' => 'text-align: right; font-weight: 600;'],
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
                    <i class="fa fa-table"></i> SLAVE Percona200.4
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
                                'contentOptions' => ['class' => 'table-name-cell', 'style' => 'text-align: left;'],
                            ],
                            [
                                'attribute' => 'table_rows',
                                'label' => 'Rows',
                                'format' => ['integer'],
                                'headerOptions' => ['style' => 'text-align: right;'],
                                'contentOptions' => ['style' => 'text-align: right; font-weight: 600;'],
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
                    <i class="fa fa-table"></i> MASTER Mysql200.7
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
                                'contentOptions' => ['class' => 'table-name-cell', 'style' => 'text-align: left;'],
                            ],
                            [
                                'attribute' => 'table_rows',
                                'label' => 'Rows',
                                'format' => ['integer'],
                                'headerOptions' => ['style' => 'text-align: right;'],
                                'contentOptions' => ['style' => 'text-align: right; font-weight: 600;'],
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
    <h4 class="section-title">🔄 Replication Status Monitor</h4>
    <div class="row">
        <!-- Card 1 -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <i class="fa fa-server"></i> SLAVE Percona Mysql5.7-200.74
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th style="width: 45%;">Slave IO State</th>
                            <td class="status-value"><?= Html::encode($slaveStatus4['Slave_IO_State']) ?></td>
                        </tr>
                        <tr>
                            <th>Master Host</th>
                            <td class="status-value"><?= Html::encode($slaveStatus4['Master_Host']) ?></td>
                        </tr>
                        <tr>
                            <th>Master User</th>
                            <td class="status-value"><?= Html::encode($slaveStatus4['Master_User']) ?></td>
                        </tr>
                        <tr>
                            <th>Master Log File</th>
                            <td class="status-value"><?= Html::encode($slaveStatus4['Master_Log_File']) ?></td>
                        </tr>
                        <tr>
                            <th>Slave IO Running</th>
                            <td><span class="label label-success"><?= Html::encode($slaveStatus4['Slave_IO_Running']) ?></span></td>
                        </tr>
                        <tr>
                            <th>Slave SQL Running</th>
                            <td><span class="label label-success"><?= Html::encode($slaveStatus4['Slave_SQL_Running']) ?></span></td>
                        </tr>
                        <tr>
                            <th>Relay Log File</th>
                            <td class="status-value"><?= Html::encode($slaveStatus4['Relay_Log_File']) ?></td>
                        </tr>
                        <tr>
                            <th>Relay Log Position</th>
                            <td class="status-value"><?= Html::encode($slaveStatus4['Relay_Log_Pos']) ?></td>
                        </tr>
                        <tr>
                            <th>Exec Master Log Pos</th>
                            <td class="status-value"><?= Html::encode($slaveStatus4['Exec_Master_Log_Pos']) ?></td>
                        </tr>
                        <tr>
                            <th>Seconds Behind Master</th>
                            <td><span class="label label-warning"><?= Html::encode($slaveStatus4['Seconds_Behind_Master']) ?></span></td>
                        </tr>
                        <tr>
                            <th>Last IO Error</th>
                            <td class="error-text"><?= Html::encode($slaveStatus4['Last_IO_Error']) ?></td>
                        </tr>
                        <tr>
                            <th>Last SQL Error</th>
                            <td class="error-text"><?= Html::encode($slaveStatus4['Last_SQL_Error']) ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <i class="fa fa-server"></i> SLAVE Mariadb10.1 200.70
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th style="width: 45%;">Slave IO State</th>
                            <td class="status-value"><?= Html::encode($slaveStatus74['Slave_IO_State']) ?></td>
                        </tr>
                        <tr>
                            <th>Master Host</th>
                            <td class="status-value"><?= Html::encode($slaveStatus74['Master_Host']) ?></td>
                        </tr>
                        <tr>
                            <th>Master User</th>
                            <td class="status-value"><?= Html::encode($slaveStatus74['Master_User']) ?></td>
                        </tr>
                        <tr>
                            <th>Master Log File</th>
                            <td class="status-value"><?= Html::encode($slaveStatus74['Master_Log_File']) ?></td>
                        </tr>
                        <tr>
                            <th>Slave IO Running</th>
                            <td><span class="label label-success"><?= Html::encode($slaveStatus74['Slave_IO_Running']) ?></span></td>
                        </tr>
                        <tr>
                            <th>Slave SQL Running</th>
                            <td><span class="label label-success"><?= Html::encode($slaveStatus74['Slave_SQL_Running']) ?></span></td>
                        </tr>
                        <tr>
                            <th>Relay Log File</th>
                            <td class="status-value"><?= Html::encode($slaveStatus74['Relay_Log_File']) ?></td>
                        </tr>
                        <tr>
                            <th>Relay Log Position</th>
                            <td class="status-value"><?= Html::encode($slaveStatus74['Relay_Log_Pos']) ?></td>
                        </tr>
                        <tr>
                            <th>Exec Master Log Pos</th>
                            <td class="status-value"><?= Html::encode($slaveStatus74['Exec_Master_Log_Pos']) ?></td>
                        </tr>
                        <tr>
                            <th>Seconds Behind Master</th>
                            <td><span class="label label-warning"><?= Html::encode($slaveStatus74['Seconds_Behind_Master']) ?></span></td>
                        </tr>
                        <tr>
                            <th>Last IO Error</th>
                            <td class="error-text"><?= Html::encode($slaveStatus74['Last_IO_Error']) ?></td>
                        </tr>
                        <tr>
                            <th>Last SQL Error</th>
                            <td class="error-text"><?= Html::encode($slaveStatus74['Last_SQL_Error']) ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-warning text-white">
                    <i class="fa fa-server"></i> SLAVE Percona200.4
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th style="width: 45%;">Slave IO State</th>
                            <td class="status-value"><?= Html::encode($slaveStatus4['Slave_IO_State']) ?></td>
                        </tr>
                        <tr>
                            <th>Master Host</th>
                            <td class="status-value"><?= Html::encode($slaveStatus4['Master_Host']) ?></td>
                        </tr>
                        <tr>
                            <th>Master User</th>
                            <td class="status-value"><?= Html::encode($slaveStatus4['Master_User']) ?></td>
                        </tr>
                        <tr>
                            <th>Master Log File</th>
                            <td class="status-value"><?= Html::encode($slaveStatus4['Master_Log_File']) ?></td>
                        </tr>
                        <tr>
                            <th>Slave IO Running</th>
                            <td><span class="label label-success"><?= Html::encode($slaveStatus4['Slave_IO_Running']) ?></span></td>
                        </tr>
                        <tr>
                            <th>Slave SQL Running</th>
                            <td><span class="label label-success"><?= Html::encode($slaveStatus4['Slave_SQL_Running']) ?></span></td>
                        </tr>
                        <tr>
                            <th>Relay Log File</th>
                            <td class="status-value"><?= Html::encode($slaveStatus4['Relay_Log_File']) ?></td>
                        </tr>
                        <tr>
                            <th>Relay Log Position</th>
                            <td class="status-value"><?= Html::encode($slaveStatus4['Relay_Log_Pos']) ?></td>
                        </tr>
                        <tr>
                            <th>Exec Master Log Pos</th>
                            <td class="status-value"><?= Html::encode($slaveStatus4['Exec_Master_Log_Pos']) ?></td>
                        </tr>
                        <tr>
                            <th>Seconds Behind Master</th>
                            <td><span class="label label-warning"><?= Html::encode($slaveStatus4['Seconds_Behind_Master']) ?></span></td>
                        </tr>
                        <tr>
                            <th>Last IO Error</th>
                            <td class="error-text"><?= Html::encode($slaveStatus4['Last_IO_Error']) ?></td>
                        </tr>
                        <tr>
                            <th>Last SQL Error</th>
                            <td class="error-text"><?= Html::encode($slaveStatus4['Last_SQL_Error']) ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>