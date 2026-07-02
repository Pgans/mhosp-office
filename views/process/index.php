<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
$this->title = '🔍 MySQL Process Monitor';
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600;700&display=swap');

/* พื้นหลัง Gradient Animation */
body {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 25%, #f093fb 50%, #4facfe 75%, #00f2fe 100%);
    background-size: 400% 400%;
    animation: gradientShift 15s ease infinite;
    font-family: 'Kanit', sans-serif;
    min-height: 100vh;
}

@keyframes gradientShift {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

/* Container */
.container-fluid {
    max-width: 1400px;
    margin: 0 auto;
    padding: 40px 20px !important;
}

/* การ์ดสไตล์ Canva */
.modern-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 24px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
    border: 2px solid rgba(255, 255, 255, 0.5);
    padding: 30px;
    animation: fadeInUp 0.8s ease-out;
}

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

/* Header Title */
.modern-card h2 {
    margin-bottom: 30px;
    font-size: 2rem;
    font-weight: 700;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    display: flex;
    align-items: center;
    gap: 12px;
}

.modern-card h2 i {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
}

/* ตารางสไตล์ Canva */
.table {
    border-collapse: separate;
    border-spacing: 0;
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

.table th {
    background: linear-gradient(135deg, #e0e7ff 0%, #f3e8ff 100%) !important;
    color: #6b7280 !important;
    font-weight: 600 !important;
    padding: 12px 10px !important;
    text-align: center;
    font-size: 11px !important;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border: none !important;
}

.table td {
    padding: 10px 8px !important;
    text-align: center;
    border-bottom: 1px solid #e2e8f0 !important;
    font-size: 12px !important;
    color: #4a5568;
    vertical-align: middle !important;
}

.table-hover tbody tr {
    transition: all 0.3s ease;
    background: white;
}

.table-striped tbody tr:nth-child(even) {
    background: #f7fafc !important;
}

.table-hover tbody tr:hover {
    background: linear-gradient(90deg, #f0f4ff 0%, #e0e7ff 100%) !important;
    transform: scale(1.01);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
}

/* ปุ่ม Kill สไตล์ Canva */
.btn-kill {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%) !important;
    border: none !important;
    color: white !important;
    padding: 6px 16px !important;
    border-radius: 20px !important;
    font-weight: 600 !important;
    font-size: 11px !important;
    transition: all 0.3s ease !important;
    box-shadow: 0 4px 12px rgba(245, 87, 108, 0.3) !important;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.btn-kill:hover {
    transform: translateY(-2px) !important;
    box-shadow: 0 6px 16px rgba(245, 87, 108, 0.4) !important;
    background: linear-gradient(135deg, #f5576c 0%, #f093fb 100%) !important;
    color: white !important;
}

.btn-kill i {
    font-size: 12px;
}

/* Pagination สไตล์ Canva */
.pagination {
    display: flex;
    justify-content: center;
    gap: 8px;
    margin-top: 30px;
    flex-wrap: wrap;
}

.pagination .page-item {
    margin: 0 !important;
}

.pagination .page-item .page-link {
    border: 2px solid #667eea !important;
    color: #667eea !important;
    padding: 10px 16px !important;
    border-radius: 12px !important;
    font-weight: 600 !important;
    transition: all 0.3s ease !important;
    background: white !important;
    min-width: 44px;
    text-align: center;
}

.pagination .page-item.active .page-link {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    border-color: #667eea !important;
    color: white !important;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4) !important;
}

.pagination .page-item .page-link:hover {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    border-color: #667eea !important;
    color: white !important;
    transform: translateY(-2px);
}

.pagination .page-item.disabled .page-link {
    opacity: 0.5;
    cursor: not-allowed;
}

/* Responsive */
@media (max-width: 768px) {
    .modern-card {
        padding: 15px;
        border-radius: 16px;
    }
    
    .modern-card h2 {
        font-size: 1.5rem;
        flex-direction: column;
        text-align: center;
    }
    
    .table {
        font-size: 11px !important;
    }
    
    .table th,
    .table td {
        padding: 8px 5px !important;
        font-size: 10px !important;
    }
    
    .btn-kill {
        padding: 4px 10px !important;
        font-size: 10px !important;
    }
}

/* เอฟเฟกต์ Scroll */
.modern-card {
    overflow-x: auto;
}

.table-responsive {
    -webkit-overflow-scrolling: touch;
}

/* Tooltip Style */
.table td[style*="text-overflow"] {
    cursor: help;
    position: relative;
}

.table td[style*="text-overflow"]:hover::after {
    content: attr(title);
    position: absolute;
    bottom: 100%;
    left: 50%;
    transform: translateX(-50%);
    background: rgba(0, 0, 0, 0.8);
    color: white;
    padding: 8px 12px;
    border-radius: 8px;
    font-size: 11px;
    white-space: nowrap;
    z-index: 1000;
    max-width: 300px;
}

/* Loading Animation */
@keyframes shimmer {
    0% { background-position: -468px 0; }
    100% { background-position: 468px 0; }
}

/* Border Animations */
.table {
    position: relative;
}

.table::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, #667eea, #764ba2, #f093fb, #4facfe);
    border-radius: 16px 16px 0 0;
}
</style>

<div class="container-fluid">
    <div class="modern-card">
        <h2>
            <i class="fas fa-server"></i> <?= Html::encode($this->title) ?>
        </h2>
        
        <?= GridView::widget([
            'tableOptions' => [
                'class' => 'table table-hover table-striped table-bordered',
            ],
            'dataProvider' => new \yii\data\ArrayDataProvider([
                'allModels' => $processList,
                'pagination' => ['pageSize' => 15],
            ]),
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'Id',
                'User',
                'Host',
                'db',
                'Command',
                'Time',
                'State',
                [
                    'attribute' => 'Info',
                    'contentOptions' => [
                        'style' => 'max-width: 300px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;',
                    ],
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{kill}',
                    'buttons' => [
                        'kill' => function ($url, $model, $key) {
                            return Html::a('<i class="fas fa-times-circle"></i> Kill', Url::to(['kill', 'id' => $model['Id']]), [
                                'class' => 'btn btn-kill btn-sm',
                                'data' => [
                                    'confirm' => 'คุณแน่ใจหรือไม่ว่าต้องการ Kill Process นี้?',
                                    'method' => 'post',
                                ],
                            ]);
                        },
                    ],
                ],
            ],
            'layout' => "{items}\n{pager}",
            'pager' => [
                'options' => ['class' => 'pagination justify-content-center'],
                'linkContainerOptions' => ['class' => 'page-item'],
                'linkOptions' => ['class' => 'page-link'],
                'activePageCssClass' => 'active',
                'disabledPageCssClass' => 'disabled',
                'prevPageLabel' => '<i class="fas fa-chevron-left"></i>',
                'nextPageLabel' => '<i class="fas fa-chevron-right"></i>',
            ],
        ]); ?>
    </div>
</div>