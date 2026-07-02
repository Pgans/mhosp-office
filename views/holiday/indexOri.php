<?php

use yii\helpers\Html;

$this->title = 'วันหยุดราชการ ปี 2568';
$this->registerCss("
    body {
        background: linear-gradient(to bottom right, #d0f0c0, #e0ffe0);
        font-family: 'Sarabun', sans-serif;
        padding: 0;
        margin: 0;
    }
    .container {
        padding: 20px;
    }
    h1 {
        text-align: center;
        color: #2e7d32;
    }
    .card {
        background: #ffffff;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        transition: box-shadow 0.3s ease;
    }
    .card:hover {
        box-shadow: 0 15px 30px rgba(0,0,0,0.2);
    }
    .card h3 {
        color: #388e3c;
        border-bottom: 2px solid #c8e6c9;
        padding-bottom: 10px;
        margin-bottom: 15px;
        text-align: center;
        background-color: #81c784;
        padding: 10px 0;
        border-radius: 8px;
    }
    .holiday-item {
        background-color: #f1f8e9;
        border-radius: 8px;
        padding: 10px;
        margin-bottom: 10px;
        font-size: 16px;
        display: flex;
        align-items: center;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .holiday-item i {
        margin-right: 10px;
        color: #388e3c;
    }
    .calendar-table {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        justify-content: space-between;
    }
    .calendar-column {
        flex: 1;
        min-width: 300px;
        padding: 10px;
        background-color: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    .calendar-column h4 {
        text-align: center;
        color: #2e7d32;
        margin-bottom: 10px;
        font-weight: bold;
        background-color: #c8e6c9;
        padding: 8px;
        border-radius: 8px;
    }
    .btn-success {
        background-color: #388e3c;
        border-color: #388e3c;
    }
");

?>

<div class="container mt-4">
    <h1><?= Html::encode($this->title) ?></h1>
    <p class="text-center"><?= Html::a('🔄 โหลดข้อมูลใหม่', ['holiday/fetch'], ['class' => 'btn btn-success']) ?></p>

    <div class="calendar-table">
        <?php foreach ($groupedHolidays as $month => $holidays): ?>
            <div class="calendar-column">
                <h4><?= date('F', strtotime("2025-$month-01")) ?> (เดือน <?= $month ?>)</h4>
                <?php foreach ($holidays as $item): ?>
                    <div class="holiday-item">
                        <i class="fas fa-calendar-day"></i> 
                        📅 <?= $item['date'] ?> - <?= Html::encode($item['title']) ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>
